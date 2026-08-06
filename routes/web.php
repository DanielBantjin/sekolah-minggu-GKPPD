<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Laravel berjalan';
});

use App\Http\Controllers\Admin\DashboardPerRoleController;

Route::get('/dashboard', DashboardPerRoleController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/dashboard/redirect', [\App\Http\Controllers\RedirectToDashboardController::class, '__invoke'])->middleware(['auth', 'verified'])->name('dashboard.redirect');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

require __DIR__.'/admin.php';

// Student routes
use App\Http\Controllers\Student\ReadingController;
use App\Http\Controllers\Student\ReflectionController as StudentReflectionController;

Route::middleware(['auth','verified','checkrole:3'])->prefix('student')->as('student.')->group(function () {
    Route::get('/reading/today', [ReadingController::class, 'today'])->name('reading.today');
    Route::post('/reading/today', [ReadingController::class, 'store'])->name('reading.today.store');
    Route::get('/reflections', [StudentReflectionController::class, 'index'])->name('reflections.index');
    Route::get('/reflections/{reflection}', [StudentReflectionController::class, 'show'])->name('reflections.show');
});

// Teacher routes
use App\Http\Controllers\Teacher\ReadingReportController;
use App\Http\Controllers\Teacher\ReflectionController as TeacherReflectionController;

Route::middleware(['auth','verified','checkrole:2'])->prefix('teacher')->as('teacher.')->group(function () {
    Route::get('/reading-report/today', [ReadingReportController::class, 'today'])->name('reading_report.today');
    Route::get('/reflections', [TeacherReflectionController::class, 'index'])->name('reflections.index');
    Route::get('/reflections/create', [TeacherReflectionController::class, 'create'])->name('reflections.create');
    Route::post('/reflections', [TeacherReflectionController::class, 'store'])->name('reflections.store');
    Route::get('/reflections/{reflection}/edit', [TeacherReflectionController::class, 'edit'])->name('reflections.edit');
    Route::put('/reflections/{reflection}', [TeacherReflectionController::class, 'update'])->name('reflections.update');
    Route::delete('/reflections/{reflection}', [TeacherReflectionController::class, 'destroy'])->name('reflections.destroy');
});



use Illuminate\Support\Facades\File;

Route::get('/render-log', function () {
    $file = storage_path('logs/laravel.log');

    if (!File::exists($file)) {
        return 'Log tidak ditemukan';
    }

    return '<pre>'.File::get($file).'</pre>';
});