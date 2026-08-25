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

    Route::prefix('course-offerings')
        ->name('course-offerings.')
        ->group(function () {
            Route::get('/', [CourseOfferingController::class, 'index'])
                ->name('index')
                ->can(PermissionType::CourseOfferingView->value);

            Route::prefix('/{courseOffering}')
                ->group(function () {
                    Route::get('/', [CourseOfferingController::class, 'show'])
                        ->name('show')
                        ->can(PermissionType::CourseOfferingView->value);

                    Route::prefix('materials')
                        ->name('materials.')
                        ->group(function () {
                            Route::get('/create', [MaterialController::class, 'create'])
                                ->name('create')
                                ->can(PermissionType::CourseOfferingMaterialCreate->value);

                            Route::post('/', [MaterialController::class, 'store'])
                                ->name('store')
                                ->can(PermissionType::CourseOfferingMaterialCreate->value);
                        });

                    Route::post('/enroll', [EnrollmentController::class, 'enroll'])
                        ->name('enroll')
                        ->can(PermissionType::CourseOfferingEnrollment->value);

                    Route::post('/drop', [EnrollmentController::class, 'drop'])
                        ->name('drop')
                        ->can(PermissionType::CourseOfferingEnrollment->value);

                    Route::get('/final-grades', [FinalGradeController::class, 'index'])
                        ->name('final-grades.index')
                        ->can(PermissionType::CourseOfferingManagement->value);
                });
        });

    Route::prefix('enrollments')
        ->name('enrollments.')
        ->group(function () {
            Route::post('{enrollment}/complete', [EnrollmentController::class, 'complete'])
                ->name('complete')
                ->can(PermissionType::CourseOfferingManagement->value);
        });

    Route::prefix('materials')
        ->name('materials.')
        ->group(function () {
            Route::get('/{material}', [MaterialController::class, 'show'])
                ->name('show')
                ->can(PermissionType::MaterialView->value);

            Route::get('/{material}/download', [MaterialController::class, 'download'])
                ->name('download')
                ->can(PermissionType::MaterialView->value);
        });
});
