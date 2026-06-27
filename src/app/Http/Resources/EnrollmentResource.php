<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course' => $this->courseOffering?->course?->name,
            'offering_id' => $this->courseOffering?->id,
            'teacher' => $this->courseOffering?->teacher?->user?->name ?? '未設定',
            'day_of_week' => $this->courseOffering?->day_of_week?->label(),
            'period' => $this->courseOffering?->period?->label(),
            'status' => $this->status?->label(),
        ];
    }
}
