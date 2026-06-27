<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Models\CourseOffering;
use App\Support\SystemClock;
use Inertia\Inertia;
use Inertia\Response;

class AssignmentController extends Controller
{
    public function show(CourseOffering $offering, Assignment $assignment): Response
    {
        abort_unless(
            $assignment->course_offering_id === $offering->id,
            404
        );

        abort_unless(
            $assignment->published_at <= SystemClock::now(),
            404
        );

        $assignment->load([
            'courseOffering',
        ]);

        $user = request()->user();

        $submissions = collect();

        if ($user->hasRole(UserRole::STUDENT)) {
            $submissions = $assignment->assignmentSubmissions()
                ->where('student_profile_id', $user->studentProfile->id)
                ->get();
        } elseif ($user->hasRole(UserRole::TEACHER) && $offering->canAccessAsTeacher($user)) {
            $submissions = $assignment->assignmentSubmissions()
                ->with('studentProfile.user')
                ->get();
        }

        $assignment->setRelation('assignmentSubmissions', $submissions);

        return Inertia::render('Assignments/Show', [
            'assignment' => (new AssignmentResource($assignment))
                ->resolve(),
        ]);
    }
}
