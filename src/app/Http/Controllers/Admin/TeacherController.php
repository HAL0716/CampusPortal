<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherStoreRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\TeacherResource;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\User;
use App\Services\PasswordGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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
            'generated_password' => session('generated_password'),
        ]);
    }

    public function appoint(): Response
    {
        $departments = Department::query()
            ->orderBy('id')
            ->get();

        return Inertia::render('Teacher/Appoint', [
            'departments' => DepartmentResource::collection($departments)->resolve(),
        ]);
    }

    public function store(TeacherStoreRequest $request, PasswordGenerator $passwordGenerator): RedirectResponse
    {
        $password = $passwordGenerator->generate();

        DB::transaction(function () use ($request, $password) {
            $user = User::create($request->userData($password));

            Teacher::create($request->teacherData($user->id));
        });

        return redirect()
            ->route('teachers.index')
            ->with('success', '教員を着任させました。')
            ->with('generated_password', $password);
    }
}
