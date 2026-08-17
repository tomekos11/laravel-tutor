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


Route::get('advertisements/filters', [AdvertisementsController::class, 'filters']);
Route::get('advertisements/form-options', [AdvertisementsController::class, 'formOptions']);

// Mutacje i lista własnych ogłoszeń tylko dla zalogowanego użytkownika (Passport)
Route::middleware('auth:api')->group(function () {
    Route::get('advertisements/mine', [AdvertisementsController::class, 'mine']);
    Route::apiResource('advertisements', AdvertisementsController::class)->only(['store', 'update', 'destroy']);
});

// Publiczne odczyty
Route::apiResource('advertisements', AdvertisementsController::class)->only(['index', 'show']);
