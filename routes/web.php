<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::resource('movies', MovieController::class)->only(['index', 'show']);

// Auth required for create/edit/delete/comment
Route::middleware('auth')->group(function () {

    // Create/update/delete movies
    Route::resource('movies', MovieController::class)->except(['index', 'show']);

    // Post comments
    Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
});

Route::get('/notifications', [NotificationController::class, 'index'])
    ->middleware('auth')
    ->name('notifications.index');
require __DIR__.'/auth.php';
