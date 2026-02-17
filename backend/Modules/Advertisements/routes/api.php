<?php

use Illuminate\Support\Facades\Route;
use Modules\Advertisements\Http\Controllers\AdvertisementsController;

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


// Publiczne odczyty
Route::apiResource('advertisements', AdvertisementsController::class)->only(['index', 'show']);

// Mutacje tylko dla zalogowanego użytkownika (Passport)
Route::middleware('auth:api')->group(function () {
    Route::apiResource('advertisements', AdvertisementsController::class)->only(['store', 'update', 'destroy']);
});
