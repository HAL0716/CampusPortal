<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\OfferingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/offerings', [OfferingController::class, 'index'])->name('offerings.index');
});
