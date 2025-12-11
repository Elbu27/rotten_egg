<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'index'])
    ->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('movies.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::pattern('movie', '[0-9]+');
Route::resource('movies', MovieController::class)->only(['index', 'show']);

// Auth required for create/edit/delete/comment
Route::middleware('auth')->group(function () {

    // Create/update/delete movies
    Route::get('/movies/create', [MovieController::class, 'create'])
    ->middleware('can:create,App\Models\Movie')
    ->name('movies.create');
    Route::post('/movies', [MovieController::class, 'store'])
    ->middleware('can:create,App\Models\Movie')
    ->name('movies.store');
    Route::get('/movies/{movie}/edit', [MovieController::class, 'edit'])
    ->middleware('can:update,movie')
    ->name('movies.edit');

    Route::put('/movies/{movie}', [MovieController::class, 'update'])
    ->middleware('can:update,movie')
    ->name('movies.update');

    Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])
    ->middleware('can:delete,movie')
    ->name('movies.destroy');


    
});


Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('movies.comments.store');

Route::get('/notifications', [NotificationController::class, 'index'])
    ->middleware('auth')
    ->name('notifications.index');
require __DIR__.'/auth.php';
