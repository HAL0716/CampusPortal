<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseOfferingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course' => $this->course?->name,
            'teacher' => $this->teacher?->user?->name ?? '未設定',
            'day_of_week' => $this->day_of_week?->label(),
            'period' => $this->period?->label(),
        ];
    }
}
