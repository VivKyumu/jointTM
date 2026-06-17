<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Task;
use App\Models\Status;
use App\Models\TaskComplexity;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\TaskComplexityController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AnalyticsExportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\EmailLogController;

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
| Dashboard & Task Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::user()->isManager()) {
            return redirect()->route('admin.tasks.dashboard');
        }

        $user = Auth::user();
        $tasksQuery = Task::with(['status', 'complexity'])
            ->where('user_id', $user->id);

        $tasks = (clone $tasksQuery)->latest()->take(8)->get();
        $allTasks = (clone $tasksQuery)->get();

        $taskStats = [
            'total' => $allTasks->count(),
            'pending' => $allTasks->where('status', 'pending')->count(),
            'in_progress' => $allTasks->where('status', 'in progress')->count(),
            'completed' => $allTasks->where('status', 'completed')->count(),
            'overdue' => $allTasks
                ->filter(fn ($task) => $task->due_at && $task->due_at->isPast() && $task->status !== 'completed')
                ->count(),
        ];

        $chartLabels = ['Pending', 'In Progress', 'Completed'];
        $chartData = [
            $taskStats['pending'],
            $taskStats['in_progress'],
            $taskStats['completed'],
        ];

        $upcomingTasks = (clone $tasksQuery)
            ->whereNotNull('due_at')
            ->where('status', '!=', 'completed')
            ->orderBy('due_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'tasks',
            'taskStats',
            'chartLabels',
            'chartData',
            'upcomingTasks'
        ));
    })->name('dashboard');

    Route::get('/task-dashboard', [AdminTaskController::class, 'taskdashboard'])->name('tasks.dashboard');

    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');
    Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::put('/tasks/{task}/update-complexity', [TaskController::class, 'updateComplexity'])->name('tasks.update-complexity');

    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::match(['POST', 'PUT'], '/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (auth + is_admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->group(function () {

        // Admin Dashboard & Settings — without 'admin.' prefix
       Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
       Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
       Route::get('/analytics/export/pdf', [AnalyticsExportController::class, 'pdf'])->name('admin.analytics.export.pdf');
       Route::get('/analytics/export/excel', [AnalyticsExportController::class, 'excel'])->name('admin.analytics.export.excel');
       Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs');
       Route::get('/email-logs', [EmailLogController::class, 'index'])->name('admin.email-logs');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::post('/users/{user}/groups', [AdminController::class, 'updateUserGroups'])->name('users.groups.update');

        /*
        |--------------------------------------------------------------------------
        | Admin Task Controller (keep 'admin.' prefix for route names)
        |--------------------------------------------------------------------------
        */
        Route::name('admin.')->group(function () {
            Route::get('/task-dashboard', [AdminTaskController::class, 'taskdashboard'])->name('tasks.dashboard');
            Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks.index');
            Route::get('/tasks/create', [AdminTaskController::class, 'create'])->name('tasks.create');
            Route::post('/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');
            Route::get('/tasks/{task}', [AdminTaskController::class, 'show'])->name('tasks.show');
            Route::get('/tasks/{task}/edit', [AdminTaskController::class, 'edit'])->name('tasks.edit');
            Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
            Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');
            Route::post('/tasks/{task}/disable', [AdminTaskController::class, 'disable'])->name('tasks.disable');
            Route::get('/tasks/{task}/status', [AdminTaskController::class, 'showAllTaskStatuses'])->name('tasks.status');
            Route::post('/tasks/{task}/status', [AdminTaskController::class, 'updateStatus'])->name('tasks.status.update');
            Route::post('/tasks/{task}/disable/confirm', [AdminTaskController::class, 'showDisableForm'])->name('tasks.disable.form');
            Route::put('/tasks/{task}/complexity', [AdminTaskController::class, 'updateComplexity'])->name('tasks.complexity.update');
        });

        /*
        |--------------------------------------------------------------------------
        | User Management (route names: users.index, etc.)
        |--------------------------------------------------------------------------
        */
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
            Route::patch('{user}/disable', 'disable')->name('disable');
            Route::patch('{user}/enable', 'enable')->name('enable');
        });

        /*
        |--------------------------------------------------------------------------
        | Group, Status, Complexity (same: no 'admin.' prefix)
        |--------------------------------------------------------------------------
        */
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
            Route::get('/groups/{group}/users', [GroupController::class, 'users'])->name('groups.users');
        });

        Route::prefix('statuses')->name('statuses.')->controller(StatusController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{status}', 'show')->name('show');
            Route::get('/{status}/edit', 'edit')->name('edit');
            Route::put('/{status}', 'update')->name('update');
            Route::delete('/{status}', 'destroy')->name('destroy');
        });

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
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
