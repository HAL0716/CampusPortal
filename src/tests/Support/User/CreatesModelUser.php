<?php

namespace Tests\Support\User;

use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

trait CreatesModelUser
{
    use UserTestData;

    protected function createUser(
        ?string $email = null,
        ?string $password = null,
        ?string $name = null,
    ): UserModel {
        return UserModel::factory()->create([
            'name' => $name ?? $this->userName(),
            'email' => $email ?? $this->userEmail(),
            'password' => Hash::make($password ?? $this->userPassword()),
        ]);
    }

    protected function toDomainUser(
        UserModel $user
    ): User {
        return app(UserRepositoryInterface::class)->findById(new UserId($user->id));
    }
}
