<?php

namespace Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Groups\Models\Group;
use Modules\Groups\Models\UserGroup;
use Modules\Tasks\Models\GroupTask;
use Modules\Tasks\Models\Task;
use Modules\Tasks\Models\TaskSubmission;

class TasksController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $query = Task::query()->with('author');

        if ($request->filled('subject')) {
            $query->where('subject', $request->get('subject'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->get('difficulty'));
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->get('search') . '%');
        }

        $tasks = $query->orderByDesc('created_at')->paginate((int) $request->get('per_page', 15));

        return apiResponse($tasks, 'Tasks fetched successfully', true, 200);
    }

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:120',
            'category' => 'nullable|string|max:120',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'attachment_path' => 'nullable|string',
        ]);

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
        ]);

        return apiResponse($task, 'Task created successfully', true, 201);
    }

    /**
     * Display a task.
     */
    public function show($id)
    {
        $task = Task::with('author')->find($id);

        if (!$task) {
            return apiResponse(null, 'Task not found', false, 404);
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

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:120',
            'category' => 'nullable|string|max:120',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'attachment_path' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $task->fill($request->only(['title', 'description', 'subject', 'category', 'difficulty', 'attachment_path']));
        $task->save();

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
