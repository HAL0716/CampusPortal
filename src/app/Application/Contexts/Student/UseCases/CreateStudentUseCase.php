<?php

namespace App\Application\Contexts\Student\UseCases;

use App\Application\Contexts\Student\Commands\CreateStudentCommand;
use App\Domain\Role\Enums\RoleType;
use App\Domain\Student\Entities\Student;
use App\Domain\Student\Repositories\StudentRepository;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Repositories\UserRoleRepository;

final readonly class CreateStudentUseCase
{
    public function __construct(
        private UserRepository $users,
        private UserRoleRepository $userRoles,
        private StudentRepository $students,
    ) {}

    public function execute(CreateStudentCommand $command): void
    {
        $user = $this->users->save(
            User::create(
                $command->email,
                $command->password,
                $command->name
            )
        );

        $this->students->save(
            Student::create(
                $user->requireId(),
                $command->departmentId,
                $command->studentNumber
            )
        );

        $this->userRoles->assign(
            $user->requireId(),
            [RoleType::STUDENT],
        );
    }
}
