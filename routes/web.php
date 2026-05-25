<?php

use App\Http\Controllers\EgateDashboardController;
use App\Http\Controllers\EgateLoginController;
use App\Http\Controllers\EgateLogoutController;

use App\Http\Controllers\EgateLogSyncController;
use App\Http\Controllers\GateEntryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SetSchEmployeeController;
use App\Http\Controllers\SetSchScheduleController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\EmployeeLogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CsrfTokenController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SinginController;

Route::get('/', [EgateDashboardController::class, 'showLogin'])->name('home');
Route::get('/admin/login', fn () => redirect()->route('signin'))->name('admin.login');
Route::post('/admin/login', [EgateDashboardController::class, 'submitLogin'])->name('admin.login.submit');
Route::match(['get', 'post'], '/sync-egate-logs', EgateLogSyncController::class)->name('egate-logs.sync');
Route::get('/signin', [EgateDashboardController::class, 'showLogin'])->name('signin');
Route::redirect('/login', '/signin')->name('login');
Route::get('/csrf-token', [CsrfTokenController::class, 'show'])->name('csrf.token');

Route::post('/signin', [SinginController::class, 'submit'])->name('signin.submit');
Route::get('/get-students/in', [EgateLoginController::class, 'getStudents'])->name('get-students.in');
Route::get('/get-students/out', [EgateLogoutController::class, 'getStudents'])->name('get-students.out');
Route::post('/gate-entries', [GateEntryController::class, 'store'])->name('gate-entries.store');
Route::get('/in', EgateLoginController::class)->name('in');
Route::get('/out', EgateLogoutController::class)->name('out');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/signin', [EgateDashboardController::class, 'forceSignin'])->name('admin.reauth');
    Route::get('/get-students', [EgateDashboardController::class, 'getStudents'])->name('get-students');
    Route::get('/welcome', EgateDashboardController::class)->name('welcome');

    Route::get('admin/dashboard', AdminDashboardController::class)->name('admin.dashboard');

    Route::prefix('admin/setup')->name('admin.setup.')->group(function () {
        Route::get('/', function () {
            return view('admin.Setup.index');
        })->name('index')->middleware('permission:setschedcehed.view|setschedem.view');

        Route::get('/schedules', [SetSchScheduleController::class, 'index'])
            ->name('schedules')
            ->middleware('permission:setschedcehed.view');
        Route::prefix('schedules')->controller(SetSchScheduleController::class)->name('schedules.')->group(function () {
            Route::get('/fetch', 'fetch')->name('fetch')->middleware('permission:setschedcehed.view');
            Route::post('/', 'store')->name('store')->middleware('permission:setschedcehed.create');
            Route::get('/{id}/details', 'details')->name('details')->middleware('permission:setschedcehed.view');
            Route::post('/{id}/details', 'saveDetails')->name('details.save')->middleware('permission:setschedcehed.update');
            Route::get('/{id}', 'show')->name('show')->middleware('permission:setschedcehed.view');
            Route::put('/{id}', 'update')->name('update')->middleware('permission:setschedcehed.update');
            Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:setschedcehed.delete');
        });

        Route::prefix('employee')->controller(SetSchEmployeeController::class)->name('employee.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('permission:setschedem.view');
            Route::get('/fetch', 'fetch')->name('fetch')->middleware('permission:setschedem.view');
            Route::put('/{id}/schedule', 'updateSchedule')->name('schedule.update')->middleware('permission:setschedem.update');
        });
    });

    Route::prefix('admin/data')->controller(DataController::class)->name('admin.data')->group(function () {
        Route::get('/', 'index')->middleware('permission:data.view');
        Route::get('/fetch', 'fetchData')->name('.fetch')->middleware('permission:data.view');
        Route::get('/print', 'print')->name('.print')->middleware('permission:data.print');
        Route::get('/export', 'export')->name('.export')->middleware('permission:data.export');
        Route::post('/', 'store')->name('.store')->middleware('permission:data.create');
        Route::get('/{id}', 'show')->name('.show')->middleware('permission:data.view');
        Route::put('/{id}', 'update')->name('.update')->middleware('permission:data.update');
        Route::delete('/{id}', 'destroy')->name('.destroy')->middleware('permission:data.delete');
    })->middleware('permission:data.view');

    Route::prefix('admin/logs')->controller(LogController::class)->name('admin.logs')->group(function () {
        Route::get('/', 'index')->middleware('permission:logs.view');
        Route::get('/fetch', 'fetchLogs')->name('.fetch')->middleware('permission:logs.view');
        Route::get('/print', 'print')->name('.print')->middleware('permission:logs.print');
        Route::get('/export', 'export')->name('.export')->middleware('permission:export.logs');
        Route::delete('/{id}', 'destroy')->name('.destroy')->middleware('permission:logs.delete');
    })->middleware('permission:logs.view');


    Route::prefix('admin/employee-logs')->controller(EmployeeLogController::class)->name('admin.employee_logs')->group(function () {
        Route::get('/', 'index')->middleware('permission:emlog.view');
        Route::get('/fetch', 'fetchLogs')->name('.fetch')->middleware('permission:emlog.view');
        Route::get('/{studentId}/logs', 'viewEmployeeLogs')->name('.view_logs')->middleware('permission:emlog.view');
        Route::get('/print', 'print')->name('.print')->middleware('permission:emlog.print');
        Route::get('/export', 'export')->name('.export')->middleware('permission:emlog.export');
        Route::delete('/{id}', 'destroy')->name('.destroy')->middleware('permission:emlog.delete');
    })->middleware('permission:emlog.view');

    Route::prefix('admin/permissions')->controller(PermissionController::class)->name('admin.permissions')->group(function () {
        Route::get('/', 'index');
        Route::get('/fetch', 'fetchPermissions')->name('.fetch');
        Route::post('/', 'store')->name('.store');
        Route::get('/{id}/edit', 'edit')->name('.edit');
        Route::put('/{id}', 'update')->name('.update');
        Route::delete('/{id}', 'destroy')->name('.destroy');
    });

    Route::prefix('admin/roles')->controller(RoleController::class)->name('admin.roles')->group(function () {
        Route::get('/', 'index')->middleware('permission:roles.view');
        Route::get('/fetch', 'fetchRoles')->name('.fetch')->middleware('permission:roles.view');
        Route::post('/', 'store')->name('.store')->middleware('permission:roles.create');
        Route::get('/{id}/edit', 'edit')->name('.edit')->middleware('permission:roles.update');
        Route::put('/{id}', 'update')->name('.update')->middleware('permission:roles.update');
        Route::delete('/{id}', 'destroy')->name('.destroy')->middleware('permission:roles.delete');
        Route::get('/{id}/permissions', 'getPermissionTree')->name('.permissions')->middleware('permission:roles.update');
        Route::put('/{id}/permissions', 'updatePermissions')->name('.permissions.update')->middleware('permission:roles.update');
    })->middleware('permission:roles.view');

    Route::prefix('admin/users')->name('admin.users.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:users.view');
        Route::get('/fetch', 'fetchUsers')->name('fetch')->middleware('permission:users.view');
        Route::get('/roles', 'getRoles')->name('roles')->middleware('permission:users.view');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:users.update');
        Route::post('/', 'store')->name('store')->middleware('permission:users.create');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:users.update');
        Route::put('/{id}/password', 'updatePassword')->name('password')->middleware('permission:users.update.pass');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:users.delete');
    })->middleware('permission:users.view');

});
