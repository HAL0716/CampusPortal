<?php

use App\Domain\Authentication\Exceptions\AuthenticationFailedException;
use App\Domain\Exceptions\DomainException;
use App\Http\Flash\Flash;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $exceptions->render(function (AuthenticationFailedException $e) {
            return back()
                ->withErrors(['email' => $e->getMessage()])
                ->onlyInput('email');
        });
        $exceptions->render(function (DomainException $e, Request $request) {
            report($e);

            if ($request->isMethod('GET')) {
                return Inertia::render('Error/Index', ['message' => $e->userMessage()])
                    ->toResponse($request)
                    ->setStatusCode($e->statusCode());
            }

            return back()->with(Flash::error($e->userMessage()));
        });
    })->create();
