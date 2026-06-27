<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\StudentProfile;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Students/Index', [
            'students' => StudentResource::collection(
                StudentProfile::with([
                    'user',
                    'department',
                    'curriculum',
                ])->get()
            )->resolve(),
        ]);
    }
}
