<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GradeController extends Controller
{
    public function index(Request $request): Response
    {
        $student = $request->user()->studentProfile;

        abort_unless($student, 404);

        return Inertia::render('Grades/Index', [
            'grades' => GradeResource::collection(
                $student->enrollments()
                    ->whereHas(
                        'courseOffering.academicTerm',
                        fn ($query) => $query->finished()
                    )
                    ->with([
                        'courseOffering.course',
                        'finalGrade',
                    ])
                    ->get()
            )->resolve(),
        ]);
    }
}
