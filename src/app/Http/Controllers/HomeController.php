<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        abort_unless($user, 403);

        return Inertia::render($user->role->HomeRoute());
    }
}
