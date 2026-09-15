<?php

namespace App\Domain\Academic\Enums;

enum Term: string
{
    case FIRST = '1';
    case SECOND = '2';
    case THIRD = '3';

    public function next(): Term
    {
        return match ($this) {
            Term::FIRST => Term::SECOND,
            Term::SECOND => Term::THIRD,
            Term::THIRD => Term::FIRST,
        };
    }

    public function advanceAcademicYear(): bool
    {
        return $this === Term::THIRD;
    }
}
