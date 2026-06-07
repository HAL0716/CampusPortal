<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home/Index', [
            'roles' => UserRole::options(),
        ]);
    }
}
