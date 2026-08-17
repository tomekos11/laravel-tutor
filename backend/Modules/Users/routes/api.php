<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\AuthController;
use Modules\Users\Http\Controllers\EducationController;
use Modules\Users\Http\Controllers\TutorListingController;

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

Route::get('tutor-listing', [TutorListingController::class, 'index']);
Route::get('tutors/{id}', [TutorListingController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::patch('me', [AuthController::class, 'updateProfile']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('me/education', [EducationController::class, 'index']);
    Route::post('me/education', [EducationController::class, 'store']);
    Route::put('me/education/{id}', [EducationController::class, 'update']);
    Route::delete('me/education/{id}', [EducationController::class, 'destroy']);
});
