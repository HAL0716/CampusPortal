<?php

namespace App\Application\Exceptions\Renderers;

use App\Http\Flash\Flash;
use App\Infrastructure\Exceptions\InfrastructureException;
use Illuminate\Http\RedirectResponse;

final class InfrastructureExceptionRenderer
{
    public function __invoke(InfrastructureException $e): RedirectResponse
    {
        return back()->with(Flash::error($e->userMessage()));
    }
}
