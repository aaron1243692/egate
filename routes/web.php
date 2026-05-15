<?php

use App\Http\Controllers\EgateDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', EgateDashboardController::class);
Route::get('/students', [EgateDashboardController::class, 'getStudents']);
