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
            'submission' => $this->whenLoaded(
                'assignmentSubmissions',
                fn () => $this->assignmentSubmissions->first()?->only([
                    'id',
                    'file_path',
                    'submitted_at',
                    'score',
                ])
            ),
        ];
    }
}
