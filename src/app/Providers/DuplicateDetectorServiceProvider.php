<?php

namespace App\Providers;

use App\Application\Contexts\CourseOffering\Duplicate\CourseOfferingDuplicateDetector;
use App\Application\Contexts\Enrollment\Duplicate\EnrollmentDuplicateDetector;
use App\Application\Contexts\FinalGrade\Duplicate\FinalGradeDuplicateDetector;
use App\Application\Contexts\Semester\Duplicate\SemesterDuplicateDetector;
use App\Application\Contexts\Student\Duplicate\StudentDuplicateDetector;
use App\Application\Contexts\User\Duplicate\UserDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlCourseOfferingDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlEnrollmentDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlFinalGradeDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlSemesterDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlStudentDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlUserDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteCourseOfferingDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteEnrollmentDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteFinalGradeDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteSemesterDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteStudentDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteUserDuplicateDetector;
use Illuminate\Support\ServiceProvider;
use LogicException;

class DuplicateDetectorServiceProvider extends ServiceProvider
{
    private const DUPLICATE_DETECTORS = [
        'mysql' => [
            UserDuplicateDetector::class => MysqlUserDuplicateDetector::class,
            StudentDuplicateDetector::class => MysqlStudentDuplicateDetector::class,
            SemesterDuplicateDetector::class => MysqlSemesterDuplicateDetector::class,
            CourseOfferingDuplicateDetector::class => MysqlCourseOfferingDuplicateDetector::class,
            EnrollmentDuplicateDetector::class => MysqlEnrollmentDuplicateDetector::class,
            FinalGradeDuplicateDetector::class => MysqlFinalGradeDuplicateDetector::class,
        ],
        'sqlite' => [
            UserDuplicateDetector::class => SqliteUserDuplicateDetector::class,
            StudentDuplicateDetector::class => SqliteStudentDuplicateDetector::class,
            SemesterDuplicateDetector::class => SqliteSemesterDuplicateDetector::class,
            CourseOfferingDuplicateDetector::class => SqliteCourseOfferingDuplicateDetector::class,
            EnrollmentDuplicateDetector::class => SqliteEnrollmentDuplicateDetector::class,
            FinalGradeDuplicateDetector::class => SqliteFinalGradeDuplicateDetector::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $driver = config('database.default');

        if (! isset(self::DUPLICATE_DETECTORS[$driver])) {
            throw new LogicException("Unsupported database driver: {$driver}");
        }

        foreach (self::DUPLICATE_DETECTORS[$driver] as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
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
