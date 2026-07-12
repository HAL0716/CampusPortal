<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Models\User as UserModel;

final class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        $model = UserModel::where('email', $email)->first();

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(UserModel $model): User
    {
        return User::reconstruct(
            new UserId((int) $model->id),
            $model->email,
            $model->password,
            $model->name
        );
    }
}
