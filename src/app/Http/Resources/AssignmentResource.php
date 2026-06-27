<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $this->file_path,
            'due_date' => $this->due_date,
            'submissions' => $this->whenLoaded(
                'assignmentSubmissions',
                fn () => $this->assignmentSubmissions->map(
                    fn ($submission) => [
                        'id' => $submission->id,
                        'student' => $submission->studentProfile?->user?->name,
                        'file_path' => $submission->file_path,
                        'submitted_at' => $submission->submitted_at,
                        'score' => $submission->score ?? null,
                    ]
                )
            ),
        ];
    }
}
