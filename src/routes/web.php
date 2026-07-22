<?php

use App\Domain\Permission\PermissionType;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
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

    Route::post('/course-offerings/{courseOffering}/enroll', [EnrollmentController::class, 'store'])
        ->name('enrollments.store');
    Route::delete('/course-offerings/{courseOffering}/drop', [EnrollmentController::class, 'drop'])
        ->name('enrollments.drop');
});
