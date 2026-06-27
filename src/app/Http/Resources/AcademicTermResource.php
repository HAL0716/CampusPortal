<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicTermResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'academic_year' => $this->academic_year,
            'term' => $this->term?->index(),
            'registration_start' => $this->registration_start,
            'registration_end' => $this->registration_end,
            'lecture_start' => $this->lecture_start,
            'lecture_end' => $this->lecture_end,
        ];
    }
}
