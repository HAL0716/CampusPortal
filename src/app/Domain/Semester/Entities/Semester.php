<?php

namespace App\Domain\Semester\Entities;

use App\Domain\Academic\Enums\Term;
use App\Domain\Semester\Exceptions\SemesterIdNotAssignedException;
use App\Domain\Semester\ValueObjects\AcademicYear;
use App\Domain\Semester\ValueObjects\SemesterId;
use DateTimeImmutable;

final readonly class Semester
{
    private function __construct(
        private ?SemesterId $id,
        private AcademicYear $academicYear,
        private Term $term,
        private DateTimeImmutable $startDate,
        private DateTimeImmutable $endDate
    ) {}

    public static function create(AcademicYear $academicYear, Term $term, DateTimeImmutable $startDate, DateTimeImmutable $endDate): self
    {
        return new self(null, $academicYear, $term, $startDate, $endDate);
    }

    public static function reconstruct(SemesterId $id, AcademicYear $academicYear, Term $term, DateTimeImmutable $startDate, DateTimeImmutable $endDate): self
    {
        return new self($id, $academicYear, $term, $startDate, $endDate);
    }

    public function nextSemester(DateTimeImmutable $endDate): self
    {
        $nextTerm = $this->term->next();

        $nextAcademicYear = $this->term->advanceAcademicYear()
            ? $this->academicYear->next()
            : $this->academicYear;

        return self::create(
            academicYear: $nextAcademicYear,
            term: $nextTerm,
            startDate: $this->endDate->modify('+1 day'),
            endDate: $endDate
        );
    }

    public function id(): ?SemesterId
    {
        return $this->id;
    }

    public function requireId(): SemesterId
    {
        if ($this->id === null) {
            throw new SemesterIdNotAssignedException;
        }

        return $this->id;
    }

    public function academicYear(): AcademicYear
    {
        return $this->academicYear;
    }

    public function term(): Term
    {
        return $this->term;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): DateTimeImmutable
    {
        return $this->endDate;
    }
}
