<?php

namespace App\Application\Exceptions\Renderers;

use App\Application\Exceptions\AuthorizationException;
use App\Http\Flash\Flash;
use Illuminate\Http\RedirectResponse;

final class AuthorizationExceptionRenderer
{
    public function __invoke(AuthorizationException $e): RedirectResponse
    {
        return back()->with(Flash::error($e->userMessage()));
    }
}
