<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ReflectionController;
use App\Http\Controllers\Admin\ReadingTrackController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\ActivityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'checkrole:1'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('reports/export-excel', [DashboardController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('reports/pdf', [DashboardController::class, 'pdf'])->name('reports.pdf');

    // Data master CRUD
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);
    Route::resource('reflections', ReflectionController::class);
    Route::resource('reading-tracks', ReadingTrackController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('finances', FinanceController::class);
    Route::resource('activities', ActivityController::class);
});

