<?php

namespace Modules\Groups\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Courses\Models\Course;
use Modules\Groups\Models\Group;
use Modules\Groups\Models\GroupNote;
use Modules\Groups\Models\UserGroup;
use Modules\Users\Models\User;

class GroupsController extends Controller
{
    /**
     * Display a listing of the groups the user belongs to.
     */
    public function index(Request $request)
    {
        $groups = Group::whereHas('userGroups', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['course.field', 'course.level'])
            ->withCount(['userGroups as members_count' => fn ($q) => $q->where('is_owner', false)])
            ->get();

        $groups->each(function (Group $group) {
            $group->is_owner = UserGroup::where('group_id', $group->id)
                ->where('user_id', Auth::id())
                ->where('is_owner', true)
                ->exists();
        });

        return apiResponse($groups, 'Groups fetched successfully', true, 200);
    }

    /**
     * Store a newly created group.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'course_id' => 'nullable|integer|exists:course__courses,id',
            'field_id' => 'nullable|integer|exists:course__fields,id|required_with:level_id',
            'level_id' => 'nullable|integer|exists:course__levels,id|required_with:field_id',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $courseId = $this->resolveCourseId($request);

        $group = DB::transaction(function () use ($request, $courseId) {
            $group = Group::create([
                'name' => $request->name,
                'course_id' => $courseId,
            ]);

            UserGroup::create([
                'user_id' => Auth::id(),
                'group_id' => $group->id,
                'is_owner' => true,
            ]);

            return $group;
        });

        $group->load('course.field', 'course.level');

        return apiResponse($group, 'Group created successfully', true, 201);
    }

    /**
     * Display the specified group.
     */
    public function show($id)
    {
        if (!$this->isGroupMember((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $group = Group::with(['userGroups.user', 'course.field', 'course.level', 'lessons' => function ($q) {
            $q->where('start_time', '>=', now())->orderBy('start_time')->limit(5);
        }])->find($id);

        if (!$group) {
            return apiResponse(null, 'Group not found', false, 404);
        }

        $group->tasks_count = DB::table('task__group_tasks')->where('group_id', $id)->count();
        $group->is_owner = $this->isGroupOwner((int) $id);

        return apiResponse($group, 'Group fetched successfully', true, 200);
    }

    /**
     * Update the specified group.
     */
    public function update($id, Request $request)
    {
        $group = Group::find($id);

        if (!$group) {
            return apiResponse(null, 'Group not found', false, 404);
        }

        if (!$this->isGroupOwner((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'course_id' => 'nullable|integer|exists:course__courses,id',
            'field_id' => 'nullable|integer|exists:course__fields,id|required_with:level_id',
            'level_id' => 'nullable|integer|exists:course__levels,id|required_with:field_id',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $group->fill($request->only(['name']));

        if ($request->filled('course_id') || $request->filled('field_id') || $request->filled('level_id')) {
            $group->course_id = $this->resolveCourseId($request, $group->course_id);
        }

        $group->save();
        $group->load('course.field', 'course.level');

        return apiResponse($group, 'Group updated successfully', true, 200);
    }

    /**
     * Remove the specified group.
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return apiResponse(null, 'Group not found', false, 404);
        }

        if (!$this->isGroupOwner((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $group->delete();

        return apiResponse(null, 'Group deleted successfully', true, 200);
    }

    /**
     * Add a member to a group.
     */
    public function addMember(Request $request, $id)
    {
        if (!$this->isGroupOwner((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'user_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        if (!$request->filled('email') && !$request->filled('user_id')) {
            return apiResponse(null, 'Either email or user_id is required', false, 422);
        }

        $user = $request->filled('user_id')
            ? User::find($request->user_id)
            : User::where('email', $request->email)->first();

        if (!$user) {
            return apiResponse(null, 'User not found', false, 404);
        }

        $exists = UserGroup::where('group_id', $id)->where('user_id', $user->id)->exists();

        if ($exists) {
            return apiResponse(null, 'User is already a member of this group', false, 409);
        }

        $userGroup = UserGroup::create([
            'user_id' => $user->id,
            'group_id' => $id,
            'is_owner' => false,
        ]);

        return apiResponse($userGroup, 'Member added successfully', true, 201);
    }

    /**
     * Remove a member from a group.
     */
    public function removeMember(Request $request, $id, $userId)
    {
        if (!$this->isGroupOwner((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        UserGroup::where('group_id', $id)
            ->where('user_id', $userId)
            ->where('is_owner', false)
            ->delete();

        return apiResponse(null, 'Member removed successfully', true, 200);
    }

    /**
     * List members of a group.
     */
    public function members($id)
    {
        if (!$this->isGroupMember((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $members = UserGroup::where('group_id', $id)
            ->where('is_owner', false)
            ->with('user')
            ->get();

        return apiResponse($members, 'Members fetched successfully', true, 200);
    }

    /**
     * List notes of a group.
     */
    public function notes($id)
    {
        if (!$this->isGroupOwner((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $notes = GroupNote::where('group_id', $id)
            ->with(['author', 'user'])
            ->orderByDesc('created_at')
            ->get();

        return apiResponse($notes, 'Notes fetched successfully', true, 200);
    }

    /**
     * Store a note for a group.
     */
    public function storeNote(Request $request, $id)
    {
        if (!$this->isGroupOwner((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'user_id' => 'nullable|integer|exists:user__users,id',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $note = GroupNote::create([
            'group_id' => $id,
            'author_id' => Auth::id(),
            'user_id' => $request->user_id,
            'content' => $request->content,
        ]);

        return apiResponse($note, 'Note created successfully', true, 201);
    }

    /**
     * Remove a note from a group.
     */
    public function destroyNote($id, $noteId)
    {
        $note = GroupNote::where('id', $noteId)->where('group_id', $id)->first();

        if (!$note) {
            return apiResponse(null, 'Note not found', false, 404);
        }

        if (Auth::id() !== $note->author_id && !$this->isGroupOwner((int) $id)) {
            return apiResponse(null, 'Forbidden', false, 403);
        }

        $note->delete();

        return apiResponse(null, 'Note deleted successfully', true, 200);
    }

    /**
     * Resolve a course_id from the request: prefer an explicit course_id, otherwise
     * find-or-create a Course matching the given field_id/level_id combo. Falls back
     * to $fallback (e.g. the group's current course_id) when nothing was provided.
     */
    private function resolveCourseId(Request $request, ?int $fallback = null): ?int
    {
        if ($request->filled('course_id')) {
            return (int) $request->course_id;
        }

        if ($request->filled('field_id') || $request->filled('level_id')) {
            $course = Course::firstOrCreate([
                'field_id' => $request->field_id,
                'level_id' => $request->level_id,
            ]);

            return $course->id;
        }

        return $fallback;
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
