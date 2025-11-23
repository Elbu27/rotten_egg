<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/movies');
});

Route::resource('movies', MovieController::class);

Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])
    ->name('comments.store');

    Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('users.show');