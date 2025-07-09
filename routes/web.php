<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\TaskComplexityController;

/*
|--------------------------------------------------------------------------
| Public Homepage
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');

    // Task Management (for regular users)
    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::put('/tasks/{task}/update-complexity', [TaskController::class, 'updateComplexity'])->name('tasks.update-complexity');

    // Profile Management
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (auth + is_admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard and Settings
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/settings', 'updateSettings')->name('settings.update');
        Route::post('/users/{user}/groups', 'updateUserGroups')->name('users.groups.update');
    });

    // Task Management (Admin)
    
    Route::controller(AdminTaskController::class)->group(function () {
        Route::get('/task-dashboard', 'taskdashboard')->name('admin.tasks.dashboard');
        Route::get('/tasks', 'index')->name('tasks.index');
        Route::get('/tasks/create', 'create')->name('tasks.create');
        Route::post('/tasks', 'store')->name('tasks.store');
        Route::get('/tasks/{task}', 'show')->name('tasks.show');
        Route::get('/tasks/{task}/edit', 'edit')->name('tasks.edit');
        Route::put('/tasks/{task}', 'update')->name('tasks.update');
        Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');

        // Custom task routes
        Route::post('/tasks/{task}/disable', 'disable')->name('tasks.disable');
        Route::get('/tasks/{task}/status', 'showAllTaskStatuses')->name('tasks.status');
        Route::post('/tasks/{task}/status', 'updateStatus')->name('tasks.status.update');
        Route::post('/tasks/{task}/disable/confirm', 'showDisableForm')->name('tasks.disable.form');
    });

    // Admin User Management
    Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
        Route::put('/{user}/update-role', 'updateRole')->name('updateRole');
        Route::put('/{user}/update-groups', 'updateGroups')->name('updateGroups');
        Route::put('/{user}/sync-groups', 'syncGroups')->name('syncGroups');
    });

    // Group Management
    Route::prefix('groups')->name('groups.')->controller(GroupController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{group}', 'show')->name('show');
        Route::get('/{group}/edit', 'edit')->name('edit');
        Route::put('/{group}', 'update')->name('update');
        Route::delete('/{group}', 'destroy')->name('destroy');
        Route::post('/{group}/add-user', 'addUser')->name('addUser');
        Route::delete('/{group}/remove-user/{user}', 'removeUser')->name('removeUser');
        Route::put('/{group}/sync-users', 'syncUsers')->name('syncUsers');
    });

    // Status Management
    Route::prefix('statuses')->name('statuses.')->controller(StatusController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{status}', 'show')->name('show');
        Route::get('/{status}/edit', 'edit')->name('edit');
        Route::put('/{status}', 'update')->name('update');
        Route::delete('/{status}', 'destroy')->name('destroy');
    });

    // Task Complexity Management
    Route::prefix('complexities')->name('complexities.')->controller(TaskComplexityController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{complexity}', 'show')->name('show');
        Route::get('/{complexity}/edit', 'edit')->name('edit');
        Route::put('/{complexity}', 'update')->name('update');
        Route::delete('/{complexity}', 'destroy')->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Mark All Notifications Read
|--------------------------------------------------------------------------
*/
Route::get('/notifications/mark-all-read', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back();
})->middleware('auth')->name('notifications.markAllAsRead');

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze/Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
