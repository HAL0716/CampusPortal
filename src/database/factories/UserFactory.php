<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'login_id' => null,
            'email' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make(config('user.password')),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (User $user) {
            if (! $user->login_id) {
                $user->login_id =
                    $user->email
                        ? Str::before($user->email, '@')
                        : fake()->unique()->userName();
            }

            if (! $user->email) {
                $user->email = $user->login_id.'@'.config('user.domain');
            }
        });
    }

    public function admin(): static
    {
        return $this->state([
            'role' => UserRole::ADMIN,
        ]);
    }

    public function student(): static
    {
        return $this->state([
            'role' => UserRole::STUDENT,
        ]);
    }

    public function teacher(): static
    {
        return $this->state([
            'role' => UserRole::TEACHER,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
