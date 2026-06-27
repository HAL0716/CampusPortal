<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department' => $this->department?->name,
            'term' => $this->term?->index(),
            'credits' => $this->credits,
            'teacher' => $this->defaultTeacher?->user?->name,
        ];
    }
}
