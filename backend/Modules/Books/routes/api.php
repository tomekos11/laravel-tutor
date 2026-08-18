<?php

use Illuminate\Support\Facades\Route;
use Modules\Books\Http\Controllers\BooksController;

Route::middleware('auth:api')->group(function () {
    Route::get('books', [BooksController::class, 'index']);
    Route::post('books', [BooksController::class, 'store'])->middleware('role:tutor');
    Route::get('books/{id}', [BooksController::class, 'show']);
    Route::put('books/{id}', [BooksController::class, 'update'])->middleware('role:tutor');
    Route::delete('books/{id}', [BooksController::class, 'destroy'])->middleware('role:tutor');
});
