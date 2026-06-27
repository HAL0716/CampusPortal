<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Resources\CourseOfferingDetailResource;
use App\Http\Resources\CourseOfferingResource;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OfferingController extends Controller
{
    public function index(Request $request): Response
    {
        $query = CourseOffering::currentTerm()
            ->with([
                'course',
                'teacher.user',
            ]);

        $teacherProfile = $request->user()->teacherProfile;

        if ($teacherProfile) {
            $query->where('teacher_id', $teacherProfile->id);
        }

        return Inertia::render('Offerings/Index', [
            'offerings' => CourseOfferingResource::collection($query->get())->resolve(),
        ]);
    }

    public function show(CourseOffering $offering): Response
    {
        $offering->load([
            'course',
            'teacher.user',
            'lectureMaterials' => fn ($query) => $query->published(),
            'assignments' => fn ($query) => $query->published(),
        ]);

        $user = request()->user();

        $students = null;

        if ($user->hasRole(UserRole::TEACHER) && $offering->canAccessAsTeacher($user)) {
            $offering->load([
                'enrollments.studentProfile.user',
                'enrollments.finalGrade',
            ]);

            $students = $offering->enrollments->map(function ($enrollment) {
                return [
                    'id' => $enrollment->studentProfile->id,
                    'name' => $enrollment->studentProfile->user->name,
                    'grade' => $enrollment->finalGrade?->grade,
                ];
            });
        }

        return Inertia::render('Offerings/Show', [
            'offering' => (new CourseOfferingDetailResource($offering))
                ->resolve(),
            'students' => $students,
        ]);
    }
}
