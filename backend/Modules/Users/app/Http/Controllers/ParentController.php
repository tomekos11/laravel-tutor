<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Groups\Models\UserGroup;
use Modules\Tasks\Models\TaskSubmission;
use Modules\Users\Models\ParentChild;
use Modules\Users\Models\Rating;
use Modules\Users\Models\Role;
use Modules\Users\Models\User;
use Modules\Users\Models\UserRole;

class ParentController extends Controller
{
    /**
     * List of children linked (approved) to the authenticated parent.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function children(Request $request)
    {
        $parent = Auth::user();

        $children = $parent->children()
            ->select(['user__users.id', 'user__users.name', 'user__users.surname', 'user__users.username', 'user__users.email', 'user__users.image', 'user__users.birthday'])
            ->get();

        return apiResponse($children, 'Children fetched successfully', true, 200);
    }

    /**
     * Pending link requests that need this user's action - either requests the
     * parent sent that are awaiting the child's confirmation, or requests a
     * child sent that are awaiting the parent's confirmation.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendingRequests(Request $request)
    {
        $user = Auth::user();

        $asParent = ParentChild::with('child:id,name,surname,username,email,image')
            ->where('parent_id', $user->id)
            ->where('status', ParentChild::STATUS_PENDING)
            ->get();

        $asChild = ParentChild::with('parent:id,name,surname,username,email,image')
            ->where('child_id', $user->id)
            ->where('status', ParentChild::STATUS_PENDING)
            ->get();

        return apiResponse([
            'sent_by_me'         => $asParent,
            'awaiting_my_action' => $asChild,
        ], 'Pending requests fetched successfully', true, 200);
    }

    /**
     * Create a brand new child (student) account owned/created by the
     * authenticated parent. Since the parent sets the credentials, the link
     * is approved immediately.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createChild(Request $request)
    {
        $parent = Auth::user();

        if (!$parent->hasRole('parent')) {
            return apiResponse(null, 'Only parent accounts can add a child', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:40|unique:user__users',
            'password' => 'required|string|min:8|confirmed',
            'name'     => 'required|string|max:120',
            'surname'  => 'required|string|max:120',
            'email'    => 'nullable|string|email|max:120|unique:user__users',
            'phone'    => 'nullable|string|max:20|unique:user__users',
            'birthday' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $child = DB::transaction(function () use ($request, $parent) {
            $child = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'name'     => $request->name,
                'surname'  => $request->surname,
                'email'    => $request->email ?? null,
                'phone'    => $request->phone ?? null,
                'birthday' => $request->birthday ?? null,
            ]);

            $studentRole = Role::where('name', 'student')->first();
            if ($studentRole) {
                UserRole::firstOrCreate(['user_id' => $child->id, 'role_id' => $studentRole->id]);
            }

            ParentChild::create([
                'parent_id'    => $parent->id,
                'child_id'     => $child->id,
                'status'       => ParentChild::STATUS_APPROVED,
                'requested_by' => ParentChild::REQUESTED_BY_PARENT,
            ]);

            return $child;
        });

        return apiResponse($child, 'Child account created and linked successfully', true, 201);
    }

    /**
     * Request to link an already existing student account (identified by
     * username, email or phone) to the authenticated parent. Requires the
     * child to confirm the request before the parent gains access.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestLink(Request $request)
    {
        $parent = Auth::user();

        if (!$parent->hasRole('parent')) {
            return apiResponse(null, 'Only parent accounts can request a link to a child', false, 403);
        }

        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string|max:120',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $identifier = $request->identifier;

        $child = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (!$child) {
            return apiResponse(null, 'No account found matching this username, e-mail or phone number', false, 404);
        }

        if ($child->id === $parent->id) {
            return apiResponse(null, 'You cannot link your own account as a child', false, 422);
        }

        $existing = ParentChild::where('parent_id', $parent->id)->where('child_id', $child->id)->first();
        if ($existing) {
            return apiResponse($existing, 'A link with this account already exists', false, 409);
        }

        $link = ParentChild::create([
            'parent_id'    => $parent->id,
            'child_id'     => $child->id,
            'status'       => ParentChild::STATUS_PENDING,
            'requested_by' => ParentChild::REQUESTED_BY_PARENT,
        ]);

        return apiResponse($link, 'Link request sent, waiting for the child to confirm', true, 201);
    }

    /**
     * Confirm or reject a pending link request. Can be called by whichever
     * side did not initiate the request (usually the child confirming a
     * parent-initiated request).
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondToRequest(Request $request, int $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:accept,reject',
        ]);

        if ($validator->fails()) {
            return apiResponse($validator->errors(), 'Validation failed', false, 422);
        }

        $link = ParentChild::where('id', $id)
            ->where('status', ParentChild::STATUS_PENDING)
            ->where(function ($q) use ($user) {
                $q->where('parent_id', $user->id)->orWhere('child_id', $user->id);
            })
            ->first();

        if (!$link) {
            return apiResponse(null, 'Pending link request not found', false, 404);
        }

        // The user who initiated the request cannot also be the one confirming it.
        $initiatorId = $link->requested_by === ParentChild::REQUESTED_BY_PARENT ? $link->parent_id : $link->child_id;
        if ($initiatorId === $user->id) {
            return apiResponse(null, 'You cannot confirm a request you sent yourself', false, 403);
        }

        if ($request->action === 'reject') {
            $link->delete();
            return apiResponse(null, 'Link request rejected', true, 200);
        }

        $link->status = ParentChild::STATUS_APPROVED;
        $link->save();

        return apiResponse($link, 'Link request accepted', true, 200);
    }

    /**
     * Remove a link between the authenticated parent and one of their
     * children (does not delete either account).
     *
     * @param Request $request
     * @param int $childId
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlink(Request $request, int $childId)
    {
        $parent = Auth::user();

        $link = ParentChild::where('parent_id', $parent->id)->where('child_id', $childId)->first();

        if (!$link) {
            return apiResponse(null, 'Link not found', false, 404);
        }

        $link->delete();

        return apiResponse(null, 'Child unlinked successfully', true, 200);
    }

    /**
     * Read-only overview of a linked child's account: groups, task
     * submissions/grades and ratings. Only accessible to a parent with an
     * approved link to that child.
     *
     * @param Request $request
     * @param int $childId
     * @return \Illuminate\Http\JsonResponse
     */
    public function childOverview(Request $request, int $childId)
    {
        $parent = Auth::user();

        $link = ParentChild::where('parent_id', $parent->id)
            ->where('child_id', $childId)
            ->where('status', ParentChild::STATUS_APPROVED)
            ->first();

        if (!$link) {
            return apiResponse(null, 'You do not have access to this account', false, 403);
        }

        $child = User::find($childId, ['id', 'name', 'surname', 'username', 'email', 'phone', 'birthday', 'image']);
        if (!$child) {
            return apiResponse(null, 'Child not found', false, 404);
        }

        $groups = UserGroup::with('group.course')
            ->where('user_id', $childId)
            ->get()
            ->map(fn (UserGroup $ug) => [
                'group_id' => $ug->group_id,
                'name'     => $ug->group->name ?? null,
                'course'   => $ug->group->course->name ?? null,
                'is_owner' => $ug->is_owner,
            ]);

        $submissions = TaskSubmission::with('groupTask.task', 'groupTask.group')
            ->where('user_id', $childId)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn (TaskSubmission $s) => [
                'id'          => $s->id,
                'task'        => $s->groupTask->task->title ?? null,
                'group'       => $s->groupTask->group->name ?? null,
                'status'      => $s->status,
                'grade'       => $s->grade,
                'feedback'    => $s->feedback,
                'submitted_at'=> $s->submitted_at,
                'due_date'    => $s->groupTask->due_date ?? null,
            ]);

        $ratings = Rating::where('tutor_id', $childId)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get(['id', 'reviewer_id', 'rating', 'description', 'created_at']);

        return apiResponse([
            'child'       => $child->only(['id', 'name', 'surname', 'username', 'email', 'phone', 'birthday', 'image']),
            'groups'      => $groups,
            'submissions' => $submissions,
            'ratings'     => $ratings,
        ], 'Child overview fetched successfully', true, 200);
    }
}
