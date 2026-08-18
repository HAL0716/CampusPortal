<?php

namespace App\Application\Contexts\Material\Commands;

use App\Application\Services\Storage\UploadFile;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\User\ValueObjects\UserId;
use DateTimeImmutable;

final readonly class CreateMaterialCommand
{
    public function __construct(
        public CourseOfferingId $courseOfferingId,
        public UserId $userId,
        public string $title,
        public ?string $description,
        public ?UploadFile $file,
        public ?DateTimeImmutable $publishDate,
    ) {}
}
