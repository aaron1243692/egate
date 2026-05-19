<?php

use App\Http\Controllers\EgateDashboardController;
use App\Http\Controllers\EgateLogSyncController;
use App\Http\Controllers\GateEntryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SinginController;

Route::get('/', EgateDashboardController::class)->name('welcome');
Route::get('/get-students', [EgateDashboardController::class, 'getStudents'])->name('get-students') ;
Route::post('/gate-entries', [GateEntryController::class, 'store'])->name('gate-entries.store');
Route::get('/admin/login', [EgateDashboardController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [EgateDashboardController::class, 'submitLogin'])->name('admin.login.submit');
// Route::get('/admin/dashboard', [EgateDashboardController::class, 'adminDashboard'])
//     ->middleware(['auth', 'role:admin'])
//     ->name('admin.dashboard');
Route::match(['get', 'post'], '/sync-egate-logs', EgateLogSyncController::class)->name('egate-logs.sync');

Route::get('/signin', [EgateDashboardController::class, 'showLogin'])->name('signin');

Route::redirect('/login', '/signin')->name('login');


Route::post('/signin', [SinginController::class, 'submit'])->name('signin.submit');

Route::middleware(['auth'])->group(function () {

    Route::get('admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::prefix('admin/data')->controller(DataController::class)->name('admin.data')->group(function () {
        Route::get('/', 'index');
        Route::get('/fetch', 'fetchData')->name('.fetch');
        Route::get('/{id}', 'show')->name('.show');
    });

    Route::prefix('admin/logs')->controller(LogController::class)->name('admin.logs')->group(function () {
        Route::get('/', 'index');
        Route::get('/fetch', 'fetchLogs')->name('.fetch');
    });

    Route::prefix('admin/permissions')->controller(PermissionController::class)->name('admin.permissions')->group(function () {
        Route::get('/', 'index');
        Route::get('/fetch', 'fetchPermissions')->name('.fetch');
        Route::post('/', 'store')->name('.store');
        Route::get('/{id}/edit', 'edit')->name('.edit');
        Route::put('/{id}', 'update')->name('.update');
        Route::delete('/{id}', 'destroy')->name('.destroy');
    });

    Route::prefix('admin/roles')->controller(RoleController::class)->name('admin.roles')->group(function () {
        Route::get('/', 'index');
        Route::get('/fetch', 'fetchRoles')->name('.fetch');
        Route::post('/', 'store')->name('.store');
        Route::get('/{id}/edit', 'edit')->name('.edit');
        Route::put('/{id}', 'update')->name('.update');
        Route::delete('/{id}', 'destroy')->name('.destroy');
        Route::get('/{id}/permissions', 'getPermissionTree')->name('.permissions');
        Route::put('/{id}/permissions', 'updatePermissions')->name('.permissions.update');
    });

    Route::prefix('admin/users')->name('admin.users.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/fetch', 'fetchUsers')->name('fetch');
        Route::get('/roles', 'getRoles')->name('roles');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::put('/{id}/password', 'updatePassword')->name('password');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::get('admin/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::put('admin/settings/{id}', [SettingController::class, 'update'])->name('admin.settings.update');

});
