<?php

namespace App\Domain\Material\Entities;

use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Material\Exceptions\MaterialFileNotAvailableException;
use App\Domain\Material\Exceptions\MaterialIdNotAssignedException;
use App\Domain\Material\ValueObjects\MaterialId;
use DateTimeImmutable;

final readonly class Material
{
    private function __construct(
        private ?MaterialId $id,
        private CourseOfferingId $courseOfferingId,
        private string $title,
        private ?string $description,
        private ?string $filePath,
        private ?DateTimeImmutable $publishDate,
    ) {}

    public static function create(
        CourseOfferingId $courseOfferingId,
        string $title,
        ?string $description,
        ?string $filePath,
        ?DateTimeImmutable $publishDate
    ): self {
        return new self(null, $courseOfferingId, $title, $description, $filePath, $publishDate);
    }

    public static function reconstruct(
        MaterialId $id,
        CourseOfferingId $courseOfferingId,
        string $title,
        ?string $description,
        ?string $filePath,
        ?DateTimeImmutable $publishDate
    ): self {
        return new self($id, $courseOfferingId, $title, $description, $filePath, $publishDate);
    }

    public function id(): ?MaterialId
    {
        return $this->id;
    }

    public function requireId(): MaterialId
    {
        if ($this->id === null) {
            throw new MaterialIdNotAssignedException;
        }

        return $this->id;
    }

    public function courseOfferingId(): CourseOfferingId
    {
        return $this->courseOfferingId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function filePath(): ?string
    {
        return $this->filePath;
    }

    public function requireFilePath(): string
    {
        if ($this->filePath === null) {
            throw new MaterialFileNotAvailableException;
        }

        return $this->filePath;
    }

    public function publishDate(): ?DateTimeImmutable
    {
        return $this->publishDate;
    }
}
