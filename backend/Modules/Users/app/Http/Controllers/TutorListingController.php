<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Models\User;

class TutorListingController extends Controller
{
    public function index(): JsonResponse
    {
        // tutorzy = role_id = 2
        $tutors = User::query()
            ->whereHas('userRoles', function ($q) {
                $q->where('role_id', 2);
            })
            ->with([
                'preference',
                'certificates',
                'userSchools.school',
                //'userRoles',
                //'answers',
                //'answerRatings',
                //'questions',
                //'questionRatings',
                //'assignments',
                //'conversations',
                //'usersConversations.conversation',
                //'messages',
                //'ratingsGiven',
                //'ratingsReceived',
            ])
            ->get();

        return response()->json($tutors);
    }
}
