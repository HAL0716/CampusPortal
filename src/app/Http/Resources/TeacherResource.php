<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'login_id' => $this->user->login_id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'department' => $this->department->name,
        ];
    }
}
