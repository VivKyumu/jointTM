<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskComplexityController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

// Public homepage
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
});

// Admin-only routes (no email verification)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard routes using controller
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/settings', 'updateSettings')->name('settings.update');
    });

    // User Management
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
        
        // Role and group management routes
        Route::put('/{user}/update-role', 'updateRole')->name('updateRole');
        Route::put('/{user}/update-groups', 'updateGroups')->name('updateGroups');
        Route::put('/{user}/sync-groups', 'syncGroups')->name('syncGroups');
    });

    // User Groups
    Route::controller(GroupController::class)->prefix('groups')->name('groups.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{group}', 'show')->name('show');
        Route::get('/{group}/edit', 'edit')->name('edit');
        Route::put('/{group}', 'update')->name('update');
        Route::delete('/{group}', 'destroy')->name('destroy');
        
        // User-group management routes
        Route::post('/{group}/add-user', 'addUser ')->name('addUser ');
        Route::delete('/{group}/remove-user/{user}', 'removeUser ')->name('removeUser ');
        Route::put('/{group}/sync-users', 'syncUsers')->name('syncUsers');
    });

    // Status Management
    Route::controller(StatusController::class)->prefix('statuses')->name('statuses.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{status}', 'show')->name('show');
        Route::get('/{status}/edit', 'edit')->name('edit');
        Route::put('/{status}', 'update')->name('update');
        Route::delete('/{status}', 'destroy')->name('destroy');
    });

    // Task Complexity
    Route::controller(TaskComplexityController::class)->prefix('complexities')->name('complexities.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{complexity}', 'show')->name('show');
        Route::get('/{complexity}/edit', 'edit')->name('edit');
        Route::put('/{complexity}', 'update')->name('update');
        Route::delete('/{complexity}', 'destroy')->name('destroy');
    });

    // Task management routes for admin
    Route::controller(TaskController::class)->prefix('tasks')->name('tasks.')->group(function () {
        Route::put('/{task}/update-status', 'updateStatus')->name('update-status');
        Route::put('/{task}/update-complexity', 'updateComplexity')->name('update-complexity');
    });
});

// Dashboard (requires auth only)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Task resource routes
    Route::resource('tasks', TaskController::class)->except(['updateStatus', 'updateComplexity']);
    
    // Task status and complexity updates for regular users
    Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])
        ->name('tasks.update-status'); // Ensure this matches the controller method
    Route::put('/tasks/{task}/update-complexity', [TaskController::class, 'updateComplexity'])
        ->name('tasks.update-complexity'); // Ensure this matches the controller method

    // Profile routes
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

// Auth scaffolding routes (without email verification)
require __DIR__.'/auth.php';
