<?php

namespace App\Application\Contexts\Student\UseCases;

use App\Application\Contexts\Student\Commands\UpdateStudentStatusCommand;
use App\Application\Services\Database\RowLockMode;
use App\Application\Services\Database\Transaction;
use App\Domain\Student\Policies\TransitionPolicy;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\User\Repositories\UserRepository;

final readonly class UpdateStudentStatusUseCase
{
    public function __construct(
        private UserRepository $users,
        private StudentRepository $students,
        private TransitionPolicy $transition,
        private Transaction $transaction,
    ) {}

    public function execute(UpdateStudentStatusCommand $command): void
    {
        $this->transaction->run(function () use ($command): void {
            $student = $this->students->get($command->studentId, RowLockMode::FOR_UPDATE);

            $this->transition->assertAllowed($command->status, $command->credits);

            $updated = $student->transitionTo($command->status);

            $this->students->save($updated);

            if ($command->status->requiresUserDeactivation()) {
                $user = $this->users->get($student->userId());

                $this->users->save($user->deactivate());
            }
        });
    }
}
