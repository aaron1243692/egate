<?php

use App\Http\Controllers\EgateDashboardController;
use App\Http\Controllers\EgateLogSyncController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SinginController;

Route::get('/', EgateDashboardController::class)->name('welcome');
Route::get('/get-students', [EgateDashboardController::class, 'getStudents'])->name('get-students') ;
Route::get('/admin/login', [EgateDashboardController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [EgateDashboardController::class, 'submitLogin'])->name('admin.login.submit');
// Route::get('/admin/dashboard', [EgateDashboardController::class, 'adminDashboard'])
//     ->middleware(['auth', 'role:admin'])
//     ->name('admin.dashboard');
Route::match(['get', 'post'], '/sync-egate-logs', EgateLogSyncController::class)->name('egate-logs.sync');

Route::get('/signin', function () {
    return view('login');
})->name('signin');


Route::post('/signin', [SinginController::class, 'submit'])->name('signin.submit');

Route::middleware(['auth'])->group(function () {

    Route::get('admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('admin/data', function () {
        return view('admin.data');
    })->name('admin.data');

    Route::get('admin/logs', function () {
        return view('admin.logs');
    })->name('admin.logs');

    Route::get('admin/permissions', function () {
        return view('admin.permissions');
    })->name('admin.permissions');

    Route::get('admin/roles', function () {
        return view('admin.roles');
    })->name('admin.roles');

    Route::get('admin/users', function () {
        return view('admin.users');
    })->name('admin.users');

});
