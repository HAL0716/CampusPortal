<?php

namespace App\Http\Controllers;

use App\Application\Authentication\LoginUseCase;
use App\Application\Authentication\LogoutUseCase;
use App\Domain\User\Exceptions\AuthenticationFailedException;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request, LoginUseCase $useCase): RedirectResponse
    {
        try {
            $useCase->execute($request->toCommand());

            $request->session()->regenerate();

            return redirect()->route('dashboard');

        } catch (AuthenticationFailedException) {

            return back()->withErrors([
                'email' => 'メールアドレスまたはパスワードが違います。',
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request, LogoutUseCase $useCase): RedirectResponse
    {
        $useCase->execute();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
