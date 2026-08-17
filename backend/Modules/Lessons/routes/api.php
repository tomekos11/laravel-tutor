<?php

use Illuminate\Support\Facades\Route;
use Modules\Lessons\Http\Controllers\LessonsController;

Route::middleware('auth:api')->group(function () {
    Route::get('lessons', [LessonsController::class, 'index']);
    Route::post('lessons', [LessonsController::class, 'store'])->middleware('role:tutor');
    Route::get('lessons/{id}', [LessonsController::class, 'show']);
    Route::put('lessons/{id}', [LessonsController::class, 'update'])->middleware('role:tutor');
    Route::post('lessons/{id}/cancel', [LessonsController::class, 'cancel'])->middleware('role:tutor');
    Route::post('lessons/{id}/report-absence', [LessonsController::class, 'reportAbsence']);
});
