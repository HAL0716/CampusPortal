<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_no' => $this->student_no,
            'name' => $this->user?->name,
            'department' => $this->department?->name,
            'degree' => $this->curriculum?->degree->label(),
            'year' => $this->curriculum?->year,
        ];
    }
}
