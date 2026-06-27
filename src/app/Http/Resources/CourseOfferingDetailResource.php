<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseOfferingDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course' => [
                'name' => $this->course?->name,
                'description' => $this->course?->description,
            ],
            'materials' => $this->lectureMaterials
                ->map(fn ($material) => [
                    'id' => $material->id,
                    'title' => $material->title,
                ]),
            'assignments' => $this->assignments
                ->map(fn ($assignment) => [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                ]),
        ];
    }
}
