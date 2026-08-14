<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthorizationServiceProvider;
use App\Providers\ClockServiceProvider;
use App\Providers\DuplicateDetectorServiceProvider;
use App\Providers\RepositoryServiceProvider;

return [
    AppServiceProvider::class,
    AuthorizationServiceProvider::class,
    ClockServiceProvider::class,
    DuplicateDetectorServiceProvider::class,
    RepositoryServiceProvider::class,
];
