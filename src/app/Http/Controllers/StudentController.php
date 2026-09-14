<?php

namespace App\Http\Controllers;

use App\Application\Contexts\Department\UseCases\ListDepartmentUseCase;
use App\Application\Contexts\Student\UseCases\CreateStudentUseCase;
use App\Application\Contexts\Student\UseCases\GetStudentUseCase;
use App\Application\Contexts\Student\UseCases\ListStudentUseCase;
use App\Application\Contexts\Student\UseCases\UpdateStudentStatusUseCase;
use App\Http\Flash\Flash;
use App\Http\Requests\Student\ShowRequest;
use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\Student\UpdateStatusRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class StudentController extends Controller
{
    public function index(ListStudentUseCase $useCase): Response
    {
        $students = $useCase->execute();

        return Inertia::render('Student/Index', [
            'students' => $students,
        ]);
    }

    public function create(ListDepartmentUseCase $useCase): Response
    {
        $departments = $useCase->execute();

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

    public function show(ShowRequest $request, GetStudentUseCase $useCase): Response
    {
        $student = $useCase->execute($request->toQuery());

        return Inertia::render('Student/Show', [
            'student' => $student,
        ]);
    }

    public function updateStatus(UpdateStatusRequest $request, UpdateStudentStatusUseCase $useCase): RedirectResponse
    {
        $useCase->execute($request->toCommand());

        return to_route('students.show', ['student' => $request->route('student')])
            ->with(Flash::success('学生ステータスを更新しました。'));
    }
}
