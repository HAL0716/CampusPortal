<?php

namespace Tests\Support\User;

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
}
