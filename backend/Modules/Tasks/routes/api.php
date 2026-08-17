<?php

use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\TasksController;

Route::middleware('auth:api')->group(function () {
    Route::get('tasks', [TasksController::class, 'index']);
    Route::post('tasks', [TasksController::class, 'store'])->middleware('role:tutor');
    Route::get('tasks/{id}', [TasksController::class, 'show']);
    Route::put('tasks/{id}', [TasksController::class, 'update'])->middleware('role:tutor');
    Route::delete('tasks/{id}', [TasksController::class, 'destroy'])->middleware('role:tutor');
    Route::post('tasks/{id}/assign', [TasksController::class, 'assignToGroup'])->middleware('role:tutor');
    Route::post('groups/{groupId}/tasks/random-assign', [TasksController::class, 'randomAssign'])->middleware('role:tutor');
    Route::get('groups/{groupId}/tasks', [TasksController::class, 'groupTasks']);
    Route::get('my-submissions', [TasksController::class, 'mySubmissions']);
    Route::post('submissions/{id}/submit', [TasksController::class, 'submit']);
    Route::post('submissions/{id}/grade', [TasksController::class, 'grade'])->middleware('role:tutor');
});
