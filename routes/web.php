<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;

// Public homepage
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (requires auth and email verification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('welcome');
});
 

// Routes that require authentication
Route::middleware(['auth'])->group(function () {

    // Task resource routes (index, create, store, show, edit, update, destroy)
    Route::resource('tasks', TaskController::class);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth scaffolding routes (login, register, password, etc.)
require __DIR__.'/auth.php';
