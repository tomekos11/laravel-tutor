<?php

namespace Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Groups\Models\Group;
use Modules\Groups\Models\UserGroup;
use Modules\Tasks\Models\GroupTask;
use Modules\Tasks\Models\Task;
use Modules\Tasks\Models\TaskAttachment;
use Modules\Tasks\Models\TaskComment;
use Modules\Tasks\Models\TaskRating;
use Modules\Tasks\Models\TaskSubmission;

class TasksController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $query = Task::query()
            ->with(['author', 'attachments', 'book'])
            ->withCount('comments')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        if ($request->filled('subject')) {
            $query->where('subject', $request->get('subject'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->get('book_id'));
        }

        if ($request->filled('book_task_number')) {
            $query->where('book_task_number', $request->get('book_task_number'));
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->get('difficulty'));
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->get('search') . '%');
        }

        switch ($request->get('sort')) {
        case 'rating':
            $query->orderByDesc('ratings_avg_rating')->orderByDesc('ratings_count');
            break;
        case 'rating_asc':
            $query->orderBy('ratings_avg_rating')->orderByDesc('ratings_count');
            break;
        case 'oldest':
            $query->orderBy('created_at');
            break;
        default:
            $query->orderByDesc('created_at');
        }

        $tasks = $query->paginate((int) $request->get('per_page', 15));

        return apiResponse($tasks, 'Tasks fetched successfully', true, 200);
    }

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), array_merge([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:120',
            'category' => 'nullable|string|max:120',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'attachment_path' => 'nullable|string',
            'book_id' => 'nullable|integer|exists:book__books,id',
            'book_task_number' => 'nullable|string|max:60',
        ], self::imageValidationRules()));

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $task = Task::create([
            'author_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'category' => $request->category,
            'difficulty' => $request->difficulty,
            'attachment_path' => $request->attachment_path,
            'book_id' => $request->book_id,
            'book_task_number' => $request->book_id ? $request->book_task_number : null,
        ]);

        $this->storeImages($request, $task);

        $task->load(['attachments', 'book']);

        return apiResponse($task, 'Task created successfully', true, 201);
    }

    /**
     * Display a task.
     */
    public function show($id)
    {
        $task = Task::with([
            'author',
            'attachments',
            'book',
            'comments' => fn ($q) => $q->with(['user', 'attachments'])->orderByDesc('is_pinned')->orderByDesc('created_at'),
        ])
            ->withAvg('ratings', 'rating')
            ->withCount(['ratings', 'comments'])
            ->find($id);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        if (Auth::check()) {
            $task->my_rating = TaskRating::where('task_id', $task->id)
                ->where('user_id', Auth::id())
                ->value('rating');
        }

        return apiResponse($task, 'Task fetched successfully', true, 200);
    }

    /**
     * Update a task.
     */
    public function update($id, Request $request)
    {
        $task = Task::find($id);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        if (Auth::id() !== $task->author_id) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), array_merge([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:120',
            'category' => 'nullable|string|max:120',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'attachment_path' => 'nullable|string',
            'book_id' => 'nullable|integer|exists:book__books,id',
            'book_task_number' => 'nullable|string|max:60',
        ], self::imageValidationRules()));

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        if ($request->hasFile('images') && !$this->canAddImages($task, count($request->file('images')))) {
            return apiResponse(null, 'Możesz dodać maksymalnie 3 załączniki do zadania', false, 422);
        }

        $task->fill($request->only(['title', 'description', 'subject', 'category', 'difficulty', 'attachment_path', 'book_id', 'book_task_number']));

        if (!$task->book_id) {
            $task->book_task_number = null;
        }

        $task->save();

        $this->storeImages($request, $task);

        $task->load(['attachments', 'book']);

        return apiResponse($task, 'Task updated successfully', true, 200);
    }

    /**
     * Delete a task.
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        if (Auth::id() !== $task->author_id) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $task->delete();

        return apiResponse(null, 'Task deleted successfully', true, 200);
    }

    /**
     * Assign a task to a group.
     */
    public function assignToGroup(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        $validator = Validator::make($request->all(), [
            'group_id' => 'required|integer|exists:group__groups,id',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        if (!$this->isGroupOwner((int) $request->group_id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $groupTask = $this->createAssignment($task, (int) $request->group_id, $request->due_date);

        return apiResponse($groupTask, 'Task assigned successfully', true, 201);
    }

    /**
     * Assign a random task to a group, matching the group's subject/category
     * (derived from its course's field/level, unless overridden in the request).
     */
    public function randomAssign(Request $request, $groupId)
    {
        if (!$this->isGroupOwner((int) $groupId)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'due_date' => 'nullable|date',
            'subject' => 'nullable|string|max:120',
            'category' => 'nullable|string|max:120',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $group = Group::with('course.field', 'course.level')->find($groupId);

        if (!$group) {
            return apiResponse(null, 'Group not found', false, 404);
        }

        $subject = $request->get('subject') ?: $group->course?->field?->name;
        $category = $request->get('category') ?: $group->course?->level?->name;

        if (!$subject && !$category) {
            return apiResponse(
                null,
                'Grupa nie ma przypisanego przedmiotu ani kategorii, po których można dobrać losowe zadanie. Podaj je ręcznie lub ustaw przedmiot grupy.',
                false,
                422
            );
        }

        $query = Task::query()
            ->whereDoesntHave('groupTasks', fn ($q) => $q->where('group_id', $groupId));

        if ($subject) {
            $query->where('subject', $subject);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $task = $query->inRandomOrder()->first();

        if (!$task) {
            return apiResponse(null, 'Brak niepowiązanych zadań w bazie pasujących do przedmiotu/kategorii tej grupy', false, 404);
        }

        $groupTask = $this->createAssignment($task, (int) $groupId, $request->due_date);

        return apiResponse($groupTask, 'Random task assigned successfully', true, 201);
    }

    /**
     * Create a GroupTask assignment plus a pending submission for every student in the group.
     */
    private function createAssignment(Task $task, int $groupId, ?string $dueDate): GroupTask
    {
        $groupTask = DB::transaction(function () use ($task, $groupId, $dueDate) {
            $groupTask = GroupTask::create([
                'task_id' => $task->id,
                'group_id' => $groupId,
                'assigned_by' => Auth::id(),
                'due_date' => $dueDate,
            ]);

            $members = UserGroup::where('group_id', $groupId)
                ->where('is_owner', false)
                ->get();

            foreach ($members as $member) {
                TaskSubmission::create([
                    'group_task_id' => $groupTask->id,
                    'user_id' => $member->user_id,
                    'status' => 'pending',
                ]);
            }

            return $groupTask;
        });

        $groupTask->load(['task', 'submissions']);

        return $groupTask;
    }

    /**
     * List tasks assigned to a group.
     */
    public function groupTasks($groupId)
    {
        if (!$this->isGroupMember((int) $groupId)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        if ($this->isGroupOwner((int) $groupId)) {
            $groupTasks = GroupTask::where('group_id', $groupId)
                ->with(['task', 'submissions.user'])
                ->get();
        } else {
            $groupTasks = GroupTask::where('group_id', $groupId)
                ->with([
                    'task',
                    'submissions' => fn ($q) => $q->where('user_id', Auth::id()),
                ])
                ->get();
        }

        return apiResponse($groupTasks, 'Group tasks fetched successfully', true, 200);
    }

    /**
     * List submissions of the authenticated user.
     */
    public function mySubmissions(Request $request)
    {
        $submissions = TaskSubmission::where('user_id', Auth::id())
            ->with(['groupTask.task', 'groupTask.group'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->get();

        return apiResponse($submissions, 'Submissions fetched successfully', true, 200);
    }

    /**
     * Submit a task submission.
     */
    public function submit(Request $request, $submissionId)
    {
        $submission = TaskSubmission::find($submissionId);

        if (!$submission) {
            return apiResponse(null, 'Submission not found', false, 404);
        }

        if ($submission->user_id !== Auth::id()) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $submission->update([
            'content' => $request->content,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return apiResponse($submission, 'Submission saved successfully', true, 200);
    }

    /**
     * Grade a task submission.
     */
    public function grade(Request $request, $submissionId)
    {
        $submission = TaskSubmission::with('groupTask')->find($submissionId);

        if (!$submission) {
            return apiResponse(null, 'Submission not found', false, 404);
        }

        if (!$this->isGroupOwner((int) $submission->groupTask->group_id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'grade' => 'required|integer|min:1|max:6',
            'feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'status' => 'done',
        ]);

        return apiResponse($submission, 'Submission graded successfully', true, 200);
    }

    /**
     * List comments for a task.
     */
    public function comments($taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        $comments = TaskComment::where('task_id', $taskId)
            ->with(['user', 'attachments'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return apiResponse($comments, 'Comments fetched successfully', true, 200);
    }

    /**
     * Add a comment to a task.
     */
    public function addComment(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        $validator = Validator::make($request->all(), array_merge([
            'content' => 'required|string|max:2000',
        ], self::imageValidationRules()));

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $comment = TaskComment::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $this->storeImages($request, $comment);

        $comment->load(['user', 'attachments']);

        return apiResponse($comment, 'Comment added successfully', true, 201);
    }

    /**
     * Delete a comment (author or task owner only).
     */
    public function deleteComment($taskId, $commentId)
    {
        $comment = TaskComment::where('task_id', $taskId)->find($commentId);

        if (!$comment) {
            return apiResponse(null, 'Comment not found', false, 404);
        }

        $task = Task::find($taskId);

        if (Auth::id() !== $comment->user_id && Auth::id() !== $task?->author_id) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $comment->delete();

        return apiResponse(null, 'Comment deleted successfully', true, 200);
    }

    /**
     * Pin a comment as the accepted answer for a task and (optionally) rate it.
     * Only the task's author can pin a comment. Pinning a new comment unpins any previous one.
     */
    public function pinComment(Request $request, $taskId, $commentId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        if (Auth::id() !== $task->author_id) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $comment = TaskComment::where('task_id', $taskId)->find($commentId);

        if (!$comment) {
            return apiResponse(null, 'Comment not found', false, 404);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        DB::transaction(function () use ($taskId, $comment, $request) {
            TaskComment::where('task_id', $taskId)
                ->where('id', '!=', $comment->id)
                ->update(['is_pinned' => false, 'pinned_rating' => null]);

            $comment->update([
                'is_pinned' => true,
                'pinned_rating' => $request->rating,
            ]);
        });

        $comment->load('user');

        return apiResponse($comment, 'Comment pinned as answer successfully', true, 200);
    }

    /**
     * Unpin the currently pinned answer comment for a task.
     */
    public function unpinComment($taskId, $commentId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        if (Auth::id() !== $task->author_id) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $comment = TaskComment::where('task_id', $taskId)->find($commentId);

        if (!$comment) {
            return apiResponse(null, 'Comment not found', false, 404);
        }

        $comment->update(['is_pinned' => false, 'pinned_rating' => null]);

        return apiResponse($comment, 'Comment unpinned successfully', true, 200);
    }

    /**
     * Rate a task (create or update the authenticated user's rating).
     */
    public function rate(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $rating = TaskRating::updateOrCreate(
            ['task_id' => $taskId, 'user_id' => Auth::id()],
            ['rating' => $request->rating]
        );

        $stats = TaskRating::where('task_id', $taskId)
            ->selectRaw('avg(rating) as avg_rating, count(*) as ratings_count')
            ->first();

        return apiResponse([
            'rating' => $rating,
            'avg_rating' => $stats?->avg_rating !== null ? round((float) $stats->avg_rating, 2) : null,
            'ratings_count' => (int) ($stats?->ratings_count ?? 0),
        ], 'Rating saved successfully', true, 200);
    }

    /**
     * Delete a single attachment (uploader or the task's author only).
     */
    public function deleteAttachment($attachmentId)
    {
        $attachment = TaskAttachment::find($attachmentId);

        if (!$attachment) {
            return apiResponse(null, 'Attachment not found', false, 404);
        }

        $attachable = $attachment->attachable;
        $task = $attachable instanceof Task ? $attachable : ($attachable?->task ?? null);

        if (Auth::id() !== $attachment->uploaded_by && Auth::id() !== $task?->author_id) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return apiResponse(null, 'Attachment deleted successfully', true, 200);
    }

    /**
     * Validation rules shared by every endpoint accepting image attachments.
     * Images only, max 10 MB each, max 3 files per request.
     */
    private static function imageValidationRules(): array
    {
        return [
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ];
    }

    /**
     * Store uploaded images as attachments of the given model, enforcing a
     * max of 3 attachments per uploader on that model.
     */
    private function storeImages(Request $request, Model $attachable): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $files = $request->file('images');

        if (!$this->canAddImages($attachable, count($files))) {
            return;
        }

        foreach ($files as $file) {
            $path = $file->store('task-attachments', 'public');

            TaskAttachment::create([
                'attachable_type' => get_class($attachable),
                'attachable_id' => $attachable->id,
                'uploaded_by' => Auth::id(),
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
        }
    }

    /**
     * Whether the authenticated user may add $newCount more attachments to $attachable
     * without exceeding the 3-files-per-person limit.
     */
    private function canAddImages(Model $attachable, int $newCount): bool
    {
        $existing = TaskAttachment::where('attachable_type', get_class($attachable))
            ->where('attachable_id', $attachable->id)
            ->where('uploaded_by', Auth::id())
            ->count();

        return ($existing + $newCount) <= 3;
    }

    private function isGroupOwner(int $groupId): bool
    {
        return UserGroup::where('group_id', $groupId)
            ->where('user_id', Auth::id())
            ->where('is_owner', true)
            ->exists();
    }

    private function isGroupMember(int $groupId): bool
    {
        return UserGroup::where('group_id', $groupId)
            ->where('user_id', Auth::id())
            ->exists();
    }
}
