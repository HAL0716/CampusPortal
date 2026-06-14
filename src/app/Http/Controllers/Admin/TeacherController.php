<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function index(): Response
    {
        $teachers = Teacher::query()
            ->with('user', 'department')
            ->orderBy('id')
            ->get();

        return Inertia::render('Teacher/Index', [
            'teachers' => TeacherResource::collection($teachers)->resolve(),
        ]);
    }
}
