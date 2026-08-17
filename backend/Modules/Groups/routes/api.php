<?php

use Illuminate\Support\Facades\Route;
use Modules\Groups\Http\Controllers\GroupsController;

Route::middleware('auth:api')->group(function () {
    Route::get('groups', [GroupsController::class, 'index']);
    Route::post('groups', [GroupsController::class, 'store'])->middleware('role:tutor');
    Route::get('groups/{id}', [GroupsController::class, 'show']);
    Route::put('groups/{id}', [GroupsController::class, 'update'])->middleware('role:tutor');
    Route::delete('groups/{id}', [GroupsController::class, 'destroy'])->middleware('role:tutor');
    Route::get('groups/{id}/members', [GroupsController::class, 'members']);
    Route::post('groups/{id}/members', [GroupsController::class, 'addMember'])->middleware('role:tutor');
    Route::delete('groups/{id}/members/{userId}', [GroupsController::class, 'removeMember'])->middleware('role:tutor');
    Route::get('groups/{id}/notes', [GroupsController::class, 'notes'])->middleware('role:tutor');
    Route::post('groups/{id}/notes', [GroupsController::class, 'storeNote'])->middleware('role:tutor');
    Route::delete('groups/{id}/notes/{noteId}', [GroupsController::class, 'destroyNote']);
});
