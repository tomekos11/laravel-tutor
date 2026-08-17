<?php

namespace Modules\Lessons\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Groups\Models\UserGroup;
use Modules\Lessons\Models\Lesson;
use Modules\Lessons\Models\UserLesson;

class LessonsController extends Controller
{
    /**
     * Display a listing of lessons for the groups the user belongs to.
     */
    public function index(Request $request)
    {
        $groupIds = UserGroup::where('user_id', Auth::id())->pluck('group_id');

        $query = Lesson::whereIn('group_id', $groupIds)->with('group')->withCount([
            'userLessons as total_students',
            'userLessons as absent_count' => fn ($q) => $q->where('absence_reported', true),
        ]);

        if ($request->filled('group_id') && $groupIds->contains((int) $request->group_id)) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('from')) {
            $query->where('start_time', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->where('start_time', '<=', $request->to);
        }

        $lessons = $query->orderBy('start_time')->paginate((int) $request->get('per_page', 20));

        return apiResponse($lessons, 'Lessons fetched successfully', true, 200);
    }

    /**
     * Store a newly created lesson.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:group__groups,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'remote' => 'nullable|boolean',
            'shared' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        if (!$this->isGroupOwner((int) $request->group_id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $lesson = DB::transaction(function () use ($request) {
            $lesson = Lesson::create([
                'group_id' => $request->group_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'remote' => $request->get('remote', true),
                'shared' => $request->get('shared', false),
                'notes' => $request->notes,
            ]);

            $members = UserGroup::where('group_id', $request->group_id)
                ->where('is_owner', false)
                ->get();

            foreach ($members as $member) {
                UserLesson::create([
                    'lesson_id' => $lesson->id,
                    'user_id' => $member->user_id,
                ]);
            }

            return $lesson;
        });

        return apiResponse($lesson, 'Lesson created successfully', true, 201);
    }

    /**
     * Display the specified lesson.
     */
    public function show($id)
    {
        $lesson = Lesson::with(['group', 'userLessons.user'])->find($id);

        if (!$lesson) {
            return apiResponse(null, 'Lesson not found', false, 404);
        }

        if (!$this->isGroupMember((int) $lesson->group_id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        return apiResponse($lesson, 'Lesson fetched successfully', true, 200);
    }

    /**
     * Update the specified lesson.
     */
    public function update($id, Request $request)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return apiResponse(null, 'Lesson not found', false, 404);
        }

        if (!$this->isGroupOwner((int) $lesson->group_id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'remote' => 'nullable|boolean',
            'shared' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $lesson->fill($request->only(['start_time', 'end_time', 'remote', 'shared', 'notes']));
        $lesson->save();

        return apiResponse($lesson, 'Lesson updated successfully', true, 200);
    }

    /**
     * Cancel the specified lesson.
     */
    public function cancel(Request $request, $id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return apiResponse(null, 'Lesson not found', false, 404);
        }

        if (!$this->isGroupOwner((int) $lesson->group_id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $lesson->update([
            'status' => 'cancelled',
            'cancelled_by' => Auth::id(),
            'cancelled_reason' => $request->reason,
            'cancelled_at' => now(),
        ]);

        return apiResponse($lesson, 'Lesson cancelled successfully', true, 200);
    }

    /**
     * Report an absence for the authenticated user for the given lesson.
     */
    public function reportAbsence(Request $request, $id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return apiResponse(null, 'Lesson not found', false, 404);
        }

        $userLesson = UserLesson::where('lesson_id', $id)->where('user_id', Auth::id())->first();

        if (!$userLesson) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        if ($lesson->start_time <= now()) {
            return apiResponse(null, 'Nie można zgłosić nieobecności po rozpoczęciu zajęć', false, 422);
        }

        if ($lesson->status === 'cancelled') {
            return apiResponse(null, 'Nie można zgłosić nieobecności dla odwołanych zajęć', false, 422);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $userLesson->update([
            'absence_reported' => true,
            'absence_reason' => $request->reason,
            'absence_reported_at' => now(),
        ]);

        return apiResponse($userLesson, 'Absence reported successfully', true, 200);
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
