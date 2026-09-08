<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

final class StudentController extends Controller
{
    public function index(): Response
    {
        $students = [
            [
                'id' => 1,
                'studentNumber' => '123456',
                'name' => 'John Doe',
                'department' => 'Computer Science',
            ],
            [
                'id' => 2,
                'studentNumber' => '789012',
                'name' => 'Jane Smith',
                'department' => 'Mathematics',
            ],
        ];

        return Inertia::render('Student/Index', [
            'students' => $students,
        ]);
    }
}
