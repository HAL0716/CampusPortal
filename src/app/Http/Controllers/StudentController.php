<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Student\UseCases\CreateStudentUseCase;
use App\Http\Flash\Flash;
use App\Http\Requests\Student\StoreRequest;
use Illuminate\Http\RedirectResponse;
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

    public function create()
    {
        $departments = [
            ['id' => 1, 'name' => 'Computer Science'],
            ['id' => 2, 'name' => 'Mathematics'],
            ['id' => 3, 'name' => 'Physics'],
        ];

        return Inertia::render('Student/Create', [
            'departments' => $departments,
        ]);
    }

    public function store(StoreRequest $request, CreateStudentUseCase $useCase): RedirectResponse
    {
        $useCase->execute($request->toCommand());

        return to_route('students.index')
            ->with(Flash::success('学生を作成しました。'));
    }
}
