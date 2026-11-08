<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ThreadController::class, 'index'])->name('home');
Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
Route::get('/thread/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::get('/thread/{thread}/topic/{topic}', [TopicController::class, 'show'])->name('topics.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/thread/{thread}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::put('/thread/{thread}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('/thread/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');
    Route::post('/thread/{thread}/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::get('/thread/{thread}/topic/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
    Route::put('/thread/{thread}/topic/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('/thread/{thread}/topic/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');
    Route::post('/thread/{thread}/topic/{topic}/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::get('/thread/{thread}/topic/{topic}/reply/{reply}/edit', [ReplyController::class, 'edit'])->name('replies.edit');
    Route::put('/thread/{thread}/topic/{topic}/reply/{reply}', [ReplyController::class, 'update'])->name('replies.update');
    Route::delete('/thread/{thread}/topic/{topic}/reply/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');

    Route::middleware('admin')->group(function () {
        Route::get('/threads/create', [ThreadController::class, 'create'])->name('threads.create');
        Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
