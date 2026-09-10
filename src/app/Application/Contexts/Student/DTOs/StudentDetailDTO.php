<?php

namespace App\Application\Contexts\Student\DTOs;

use App\Domain\Student\Enums\StudentStatus;

final readonly class StudentDetailDTO
{
    /**
     * @param  array<StudentStatus>  $transitions
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $studentNumber,
        public string $department,
        public int $credits, // 今は取得講義数
        public string $status,
        public array $transitions,
    ) {}
}
