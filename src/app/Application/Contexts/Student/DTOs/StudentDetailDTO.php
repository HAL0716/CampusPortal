<?php

namespace App\Application\Contexts\Student\DTOs;

final readonly class StudentDetailDTO
{
    /**
     * @param  array<array{value: string, label: string}>  $transitions
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
