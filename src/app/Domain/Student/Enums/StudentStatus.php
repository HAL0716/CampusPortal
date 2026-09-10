<?php

namespace App\Domain\Student\Enums;

enum StudentStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case EXPELLED = 'expelled';
    case GRADUATED = 'graduated';

    /** @return array<StudentStatus> */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::ACTIVE => [self::SUSPENDED, self::EXPELLED, self::GRADUATED],
            self::SUSPENDED => [self::ACTIVE, self::EXPELLED],
            self::EXPELLED => [],
            self::GRADUATED => [],
        };
    }

    public function canTransitionTo(StudentStatus $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions(), true);
    }
}
