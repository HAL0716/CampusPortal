<?php

use App\Domain\Permission\Enums\PermissionType;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseOfferingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FinalGradeController;
use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->can(PermissionType::DashboardView->value);

    Route::get('/course-offerings', [CourseOfferingController::class, 'index'])
        ->name('course-offerings.index')
        ->can(PermissionType::CourseOfferingView->value);

    Route::get('/course-offerings/{id}', [CourseOfferingController::class, 'show'])
        ->name('course-offerings.show')
        ->can(PermissionType::CourseOfferingView->value);

    Route::get('/course-offerings/{id}/materials/create', [MaterialController::class, 'create'])
        ->name('course-offerings.materials.create')
        ->can(PermissionType::CourseOfferingMaterialCreate->value);

    Route::post('/course-offerings/{id}/materials', [MaterialController::class, 'store'])
        ->name('course-offerings.materials.store')
        ->can(PermissionType::CourseOfferingMaterialCreate->value);

    Route::post('/course-offerings/{courseOffering}/enroll', [EnrollmentController::class, 'enroll'])
        ->name('course-offerings.enroll')
        ->can(PermissionType::CourseOfferingEnrollment->value);
    Route::post('/course-offerings/{courseOffering}/drop', [EnrollmentController::class, 'drop'])
        ->name('course-offerings.drop')
        ->can(PermissionType::CourseOfferingEnrollment->value);
    Route::post('/enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])
        ->name('enrollments.complete')
        ->can(PermissionType::CourseOfferingManagement->value);

    Route::get('/course-offerings/{id}/final-grades', [FinalGradeController::class, 'index'])
        ->name('course-offerings.final-grades.index')
        ->can(PermissionType::CourseOfferingManagement->value);
});
