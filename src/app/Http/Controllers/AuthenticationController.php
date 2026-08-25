<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Authentication\UseCases\LoginUseCase;
use App\Application\Contexts\Authentication\UseCases\LogoutUseCase;
use App\Http\Requests\Authentication\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Authentication/Login');
    }

    public function login(LoginRequest $request, LoginUseCase $useCase): RedirectResponse
    {
        $useCase->execute($request->toCommand());

        $request->session()->regenerate();

        return to_route('dashboard');
    }

    public function logout(Request $request, LogoutUseCase $useCase): RedirectResponse
    {
        $useCase->execute();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('login');
    }
}
