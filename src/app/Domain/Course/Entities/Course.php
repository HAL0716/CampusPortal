<?php

namespace App\Domain\Course\Entities;

use App\Domain\Course\Exceptions\CourseIdNotAssignedException;
use App\Domain\Course\ValueObjects\CourseId;

final readonly class Course
{
    private function __construct(
        private ?CourseId $id,
    ) {}

    public static function create(): self
    {
        return new self(null);
    }

    public static function reconstruct(CourseId $id): self
    {
        return new self($id);
    }

    public function id(): ?CourseId
    {
        return $this->id;
    }

    public function requireId(): CourseId
    {
        if ($this->id === null) {
            throw new CourseIdNotAssignedException;
        }

        return $this->id;
    }
}
