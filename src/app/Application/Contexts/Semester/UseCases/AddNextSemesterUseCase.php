<?php

namespace App\Application\Contexts\Semester\UseCases;

use App\Application\Contexts\Semester\Commands\AddNextSemesterCommand;
use App\Application\Services\Database\Transaction;
use App\Domain\Course\Repositories\CourseRepository;
use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\Repositories\CourseOfferingRepository;
use App\Domain\Semester\Repositories\SemesterRepository;

final readonly class AddNextSemesterUseCase
{
    public function __construct(
        private SemesterRepository $semesters,
        private CourseRepository $courses,
        private CourseOfferingRepository $courseOfferings,
        private Transaction $transaction,
    ) {}

    public function execute(AddNextSemesterCommand $command): void
    {
        $this->transaction->run(function () use ($command): void {
            $latest = $this->semesters->getLatest();

            $next = $latest->nextSemester($command->endDate);

            $next = $this->semesters->save($next);

            $courses = $this->courses->getByTerm($next->term());

            foreach ($courses as $course) {
                $courseOffering = CourseOffering::create(
                    courseId: $course->requireId(),
                    semesterId: $next->requireId(),
                );

                $this->courseOfferings->save($courseOffering);
            }
        });
    }
}
