<?php

namespace App\Application\Exceptions\Renderers;

use App\Domain\Authentication\Exceptions\AuthenticationException;
use Illuminate\Http\RedirectResponse;

final class AuthenticationExceptionRenderer
{
    public function __invoke(AuthenticationException $e): RedirectResponse
    {
        return back()->withErrors(['email' => $e->userMessage()])->onlyInput('email');
    }
}
