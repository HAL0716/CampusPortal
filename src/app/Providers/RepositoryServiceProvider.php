<?php

namespace App\Providers;

use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\Enrollment\Repositories\EnrollmentRepository;
use App\Domain\FinalGrade\Repositories\FinalGradeRepository;
use App\Domain\Material\Repositories\MaterialRepository;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Semester\Repositories\SemesterRepository;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\Teacher\Repositories\TeacherRepository;
use App\Domain\User\Repositories\UserRepository;
use App\Infrastructure\Repositories\EloquentCourseOfferingRepository;
use App\Infrastructure\Repositories\EloquentEnrollmentRepository;
use App\Infrastructure\Repositories\EloquentFinalGradeRepository;
use App\Infrastructure\Repositories\EloquentMaterialRepository;
use App\Infrastructure\Repositories\EloquentPermissionRepository;
use App\Infrastructure\Repositories\EloquentSemesterRepository;
use App\Infrastructure\Repositories\EloquentStudentRepository;
use App\Infrastructure\Repositories\EloquentTeacherRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    private const REPOSITORIES = [
        UserRepository::class => EloquentUserRepository::class,
        PermissionRepository::class => EloquentPermissionRepository::class,
        StudentRepository::class => EloquentStudentRepository::class,
        TeacherRepository::class => EloquentTeacherRepository::class,
        SemesterRepository::class => EloquentSemesterRepository::class,
        CourseOfferingRepository::class => EloquentCourseOfferingRepository::class,
        MaterialRepository::class => EloquentMaterialRepository::class,
        EnrollmentRepository::class => EloquentEnrollmentRepository::class,
        FinalGradeRepository::class => EloquentFinalGradeRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach (self::REPOSITORIES as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
