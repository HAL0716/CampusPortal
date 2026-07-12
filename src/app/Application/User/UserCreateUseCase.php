<?php

namespace App\Application\User;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;

class UserCreateUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {}

    public function execute(UserCreateCommand $command): User
    {
        return $this->users->save(
            User::create(
                $command->email,
                $command->password,
                $command->name
            )
        );
    }
}
