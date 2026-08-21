<?php

use App\Application\Exceptions\Renderers\AuthenticationExceptionRenderer;
use App\Application\Exceptions\Renderers\AuthorizationExceptionRenderer;
use App\Application\Exceptions\Renderers\DomainExceptionRenderer;
use App\Application\Exceptions\Renderers\InfrastructureExceptionRenderer;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        foreach ([
            AuthenticationExceptionRenderer::class,
            AuthorizationExceptionRenderer::class,
            DomainExceptionRenderer::class,
            InfrastructureExceptionRenderer::class,
        ] as $renderer) {
            $exceptions->render(app($renderer));
        }
    })->create();
