<?php

namespace App\Application\Contexts\Semester\UseCases;

use App\Application\Contexts\Semester\Commands\AddNextSemesterCommand;
use App\Domain\Semester\Repositories\SemesterRepository;

final readonly class AddNextSemesterUseCase
{
    public function __construct(
        private SemesterRepository $semesters,
    ) {}

    public function execute(AddNextSemesterCommand $command): void
    {
        $latest = $this->semesters->getLatest();

        $next = $latest->nextSemester($command->endDate);

        $this->semesters->save($next);
    }
}
