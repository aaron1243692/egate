<?php

use App\Http\Controllers\EgateDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', EgateDashboardController::class)->name('welcome');
Route::get('/get-students', [EgateDashboardController::class, 'getStudents'])->name('get-students') ;

