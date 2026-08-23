<?php

namespace App\Application\Exceptions\Renderers;

use App\Domain\Exceptions\DomainException;
use App\Http\Flash\Flash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

final class DomainExceptionRenderer
{
    public function __invoke(DomainException $e, Request $request): Response
    {
        $message = $e->userMessage();

        if ($request->isMethod('GET')) {
            return Inertia::render('Error/Index', ['message' => $message])
                ->toResponse($request)
                ->setStatusCode($e->statusCode());
        }

        return back()->with(Flash::error($message));
    }
}
