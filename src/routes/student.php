<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OfferingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/offerings', [OfferingController::class, 'index'])->name('offerings.index');
    Route::get('/offerings/{offering}', [OfferingController::class, 'show'])->name('offerings.show');
    Route::get('/offerings/{offering}/materials/{material}', [MaterialController::class, 'show'])->name('offerings.materials.show');
});
