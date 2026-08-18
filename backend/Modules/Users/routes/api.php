<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\AuthController;
use Modules\Users\Http\Controllers\ConversationsController;
use Modules\Users\Http\Controllers\EducationController;
use Modules\Users\Http\Controllers\ParentController;
use Modules\Users\Http\Controllers\TutorListingController;
use Modules\Users\Http\Controllers\UsersController;

// use Modules\Users\Http\Controllers\AuthController;
/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);

Route::get('tutor-listing', [TutorListingController::class, 'index']);
Route::get('tutors/{id}', [TutorListingController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::patch('me', [AuthController::class, 'updateProfile']);
    Route::post('me/avatar', [AuthController::class, 'uploadAvatar']);
    Route::delete('me/avatar', [AuthController::class, 'deleteAvatar']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('me/education', [EducationController::class, 'index']);
    Route::post('me/education', [EducationController::class, 'store']);
    Route::put('me/education/{id}', [EducationController::class, 'update']);
    Route::delete('me/education/{id}', [EducationController::class, 'destroy']);

    Route::get('users/search', [UsersController::class, 'search']);

    Route::get('conversations', [ConversationsController::class, 'index']);
    Route::post('conversations', [ConversationsController::class, 'store']);
    Route::get('conversations/{id}', [ConversationsController::class, 'show']);
    Route::get('conversations/{id}/messages', [ConversationsController::class, 'messages']);
    Route::post('conversations/{id}/messages', [ConversationsController::class, 'sendMessage']);
    Route::post('conversations/{id}/read', [ConversationsController::class, 'markAsRead']);
    Route::post('conversations/{id}/participants', [ConversationsController::class, 'addParticipant']);

    // Requests linking parent <-> child accounts to each other; open to both
    // roles since either side can initiate/confirm a link request.
    Route::get('parent/requests', [ParentController::class, 'pendingRequests']);
    Route::post('parent/requests/{id}/respond', [ParentController::class, 'respondToRequest']);

    Route::middleware('role:parent')->group(function () {
        Route::get('parent/children', [ParentController::class, 'children']);
        Route::post('parent/children', [ParentController::class, 'createChild']);
        Route::post('parent/children/link', [ParentController::class, 'requestLink']);
        Route::delete('parent/children/{childId}', [ParentController::class, 'unlink']);
        Route::get('parent/children/{childId}/overview', [ParentController::class, 'childOverview']);
    });
});
