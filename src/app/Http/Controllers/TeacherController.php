<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherResource;
use App\Models\TeacherProfile;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Teachers/Index', [
            'teachers' => TeacherResource::collection(
                TeacherProfile::with([
                    'user',
                    'department',
                ])->get()
            )->resolve(),
        ]);
    }
}
