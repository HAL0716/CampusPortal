<?php

namespace App\Application\Contexts\Student\UseCases;

use App\Application\Contexts\Student\Commands\UpdateStudentStatusCommand;
use App\Domain\Student\Policies\TransitionPolicy;
use App\Domain\Student\Repositories\StudentRepository;

final readonly class UpdateStudentStatusUseCase
{
    public function __construct(
        private StudentRepository $students,
        private TransitionPolicy $transition,
    ) {}

    public function execute(UpdateStudentStatusCommand $command): void
    {
        $student = $this->students->get($command->studentId);

        $updated = $student->transitionTo($command->status);

        $this->transition->assertAllowed($command->status, $command->credits);

        $this->students->save($updated);
    }
}
