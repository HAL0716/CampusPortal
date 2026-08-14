<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthorizationServiceProvider;
use App\Providers\ClockServiceProvider;
use App\Providers\RepositoryServiceProvider;

return [
    AppServiceProvider::class,
    AuthorizationServiceProvider::class,
    ClockServiceProvider::class,
    RepositoryServiceProvider::class,
];
