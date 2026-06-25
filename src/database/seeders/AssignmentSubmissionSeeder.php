<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Database\Seeder;

class AssignmentSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        Assignment::whereHas(
            'courseOffering',
            fn ($query) => $query->where('academic_term_id', AcademicTerm::current()->id)
        )
            ->with('courseOffering.enrollments')
            ->each(function (Assignment $assignment) {
                $assignment
                    ->courseOffering
                    ->enrollments
                    ->each(function ($enrollment) use ($assignment) {
                        AssignmentSubmission::firstOrCreate(
                            [
                                'assignment_id' => $assignment->id,
                                'student_profile_id' => $enrollment->student_profile_id,
                            ],
                            [
                                'file_path' => "submissions/{$assignment->id}/{$enrollment->student_profile_id}.pdf",
                                'submitted_at' => $assignment->published_at
                                    ->copy()->addDays(fake()->numberBetween(0, $assignment->due_date->diffInDays($assignment->published_at))),
                                'score' => fake()->optional()
                                    ->numberBetween(60, 100),
                            ]
                        );

                    });

            });
    }
}
