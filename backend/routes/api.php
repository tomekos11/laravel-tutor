<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Odbiera zgłoszenia błędów klienta (frontend/src/boot/error-reporting.ts) i loguje je,
 * żeby błędy JS w przeglądarce były widoczne w logach backendu zamiast ginąć bez śladu.
 */
Route::post('/client-error', function (Request $request) {
    Log::warning('Client error reported', [
        'type' => $request->input('type'),
        'message' => $request->input('message'),
        'stack' => $request->input('stack'),
        'url' => $request->input('url'),
        'ip' => $request->ip(),
    ]);

    return response()->noContent();
});
