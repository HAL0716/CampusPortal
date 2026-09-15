<?php

use App\Domain\Permission\Enums\PermissionType;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CourseOfferingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FinalGradeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticationController::class, 'index'])
        ->name('login');

    Route::post('/login', [AuthenticationController::class, 'login'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->can(PermissionType::DashboardView->value);

    Route::prefix('students')
        ->name('students.')
        ->can(PermissionType::StudentManage->value)
        ->group(function () {
            Route::get('/', [StudentController::class, 'index'])
                ->name('index');

            Route::get('/create', [StudentController::class, 'create'])
                ->name('create');

            Route::post('/', [StudentController::class, 'store'])
                ->name('store');

            Route::prefix('/{student}')
                ->group(function () {
                    Route::get('/', [StudentController::class, 'show'])
                        ->name('show');

                    Route::patch('/status', [StudentController::class, 'updateStatus'])
                        ->name('update.status');
                });
        });

    Route::prefix('semesters')
        ->name('semesters.')
        ->can(PermissionType::SemesterManage->value)
        ->group(function () {
            Route::get('/', [SemesterController::class, 'index'])
                ->name('index');

            Route::get('/create', [SemesterController::class, 'create'])
                ->name('create');

            Route::post('/', [SemesterController::class, 'store'])
                ->name('store');
        });

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
                            Route::post('/', [MaterialController::class, 'store'])
                                ->name('store')
                                ->can(PermissionType::MaterialCreate->value);
                        });

                    Route::post('/enroll', [EnrollmentController::class, 'enroll'])
                        ->name('enroll')
                        ->can(PermissionType::EnrollmentManage->value);

                    Route::post('/drop', [EnrollmentController::class, 'drop'])
                        ->name('drop')
                        ->can(PermissionType::EnrollmentManage->value);

                    Route::get('/final-grades', [FinalGradeController::class, 'index'])
                        ->name('final-grades.index')
                        ->can(PermissionType::FinalGradeCreate->value);
                });
        });

    Route::prefix('enrollments')
        ->name('enrollments.')
        ->group(function () {
            Route::post('{enrollment}/complete', [EnrollmentController::class, 'complete'])
                ->name('complete')
                ->can(PermissionType::FinalGradeCreate->value);
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
