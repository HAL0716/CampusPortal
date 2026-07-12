<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function login(): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    public function logout(): RedirectResponse
    {
        return redirect()->route('login');
    }
}
