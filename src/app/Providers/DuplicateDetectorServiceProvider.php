<?php

namespace App\Providers;

use App\Application\Contexts\User\UserDuplicateDetectorInterface;
use App\Application\Enrollment\EnrollmentDuplicateDetectorInterface;
use App\Application\FinalGrade\FinalGradeDuplicateDetectorInterface;
use App\Infrastructure\Database\Mysql\MysqlEnrollmentDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlFinalGradeDuplicateDetector;
use App\Infrastructure\Database\Mysql\MysqlUserDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteEnrollmentDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteFinalGradeDuplicateDetector;
use App\Infrastructure\Database\Sqlite\SqliteUserDuplicateDetector;
use Illuminate\Support\ServiceProvider;
use LogicException;

class DuplicateDetectorServiceProvider extends ServiceProvider
{
    private const DUPLICATE_DETECTORS = [
        'mysql' => [
            UserDuplicateDetectorInterface::class => MysqlUserDuplicateDetector::class,
            EnrollmentDuplicateDetectorInterface::class => MysqlEnrollmentDuplicateDetector::class,
            FinalGradeDuplicateDetectorInterface::class => MysqlFinalGradeDuplicateDetector::class,
        ],
        'sqlite' => [
            UserDuplicateDetectorInterface::class => SqliteUserDuplicateDetector::class,
            EnrollmentDuplicateDetectorInterface::class => SqliteEnrollmentDuplicateDetector::class,
            FinalGradeDuplicateDetectorInterface::class => SqliteFinalGradeDuplicateDetector::class,
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
