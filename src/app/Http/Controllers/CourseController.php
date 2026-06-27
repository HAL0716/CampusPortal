<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Courses/Index', [
            'courses' => CourseResource::collection(
                Course::with([
                    'department',
                    'defaultTeacher.user',
                ])->get()
            )->resolve(),
        ]);
    }
}
