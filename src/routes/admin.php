<?php

use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/academic-terms', [AcademicTermController::class, 'index'])->name('academic-terms.index');
    Route::get('/offerings', [OfferingController::class, 'index'])->name('offerings.index');
});
