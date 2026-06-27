<?php

namespace App\Http\Controllers;

use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EnrollmentController extends Controller
{
    public function index(Request $request): Response
    {
        $student = $request->user()->studentProfile;

        if (! $student) {
            abort(404, 'Student profile not found.');
        }

        return Inertia::render('Enrollments/Index', [
            'enrollments' => EnrollmentResource::collection(
                Enrollment::query()
                    ->forStudent($student)
                    ->currentTerm()
                    ->with([
                        'courseOffering.course',
                        'courseOffering.teacher.user',
                    ])
                    ->get()
            )->resolve(),
        ]);
    }
}
