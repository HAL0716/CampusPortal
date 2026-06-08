<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserGrade;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'role' => $request->query('role', UserRole::STUDENT->value),
            'grades' => UserGrade::options(),
            'departments' => Department::options(),
        ]);
    }

    public function store(Request $request)
    {
        return back()->with('success', '学生情報を反映しました');
    }
}
