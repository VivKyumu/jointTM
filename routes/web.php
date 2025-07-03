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

/*
|--------------------------------------------------------------------------
| Public Homepage
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Tasks
    Route::resource('tasks', TaskController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::controller(AdminController::class)->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/tasks', 'index')->name('tasks.index');
    Route::get('/tasks/{task}/edit', 'edit')->name('tasks.edit');
    Route::put('/tasks/{task}', 'update')->name('tasks.update');
    Route::post('/tasks/{task}/disable', 'disable')->name('tasks.disable');
    Route::get('/tasks/{task}/status', [AdminController::class, 'showAllTaskStatuses'])->name('tasks.status');
    Route::post('/tasks/{task}/status', [AdminController::class, 'updateStatus'])->name('tasks.status.update');
    Route::post('/tasks/{task}/disable/confirm', [AdminController::class, 'showDisableForm'])->name('tasks.disable.form');
     
    Route::delete('/task/{task}/delete', [AdminController::class, 'destroy'])->name('tasks.destroy');
    
   
});

Route::get('/admin', [AdminController::class, 'dashboard'])->middleware('auth')->name('admin.home');


Route::get('/notifications/mark-all-read', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.markAllAsRead');







/*
|--------------------------------------------------------------------------
| Status Management Routes (Settings Section)
|--------------------------------------------------------------------------
*/
// Route::prefix('settings1')->name('status.')->group(function () {
//     Route::get('/status', [StatusController::class, 'index'])->name('index');
//     Route::get('/status/create', [StatusController::class, 'create'])->name('create');
//     Route::post('/status', [StatusController::class, 'store'])->name('store');
//     Route::get('/status/{status}', [StatusController::class, 'show'])->name('show');
//     Route::get('/status/{status}/edit', [StatusController::class, 'edit'])->name('edit');
//     Route::match(['put', 'patch'], '/status/{status}', [StatusController::class, 'update'])->name('update');
//     Route::delete('/status/{status}', [StatusController::class, 'destroy'])->name('destroy');
// });

/*
|--------------------------------------------------------------------------
| Group Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Auth Scaffolding (Laravel Breeze or Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Fallback Home Route
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'index'])->name('home');
