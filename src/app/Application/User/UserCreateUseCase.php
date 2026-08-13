<?php

namespace App\Application\User;

use App\Domain\User\Entities\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserEmail;
use App\Domain\User\ValueObjects\UserPassword;

class UserCreateUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {}

    public function execute(UserCreateCommand $command): User
    {
        return $this->users->save(
            User::create(
                new UserEmail($command->email),
                UserPassword::create($command->password),
                $command->name
            )
        );
    }
}
