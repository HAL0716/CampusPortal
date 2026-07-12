<?php

namespace Database\Seeders;

use App\Application\User\UserCreateCommand;
use App\Application\User\UserCreateUseCase;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const ALLOWED_ENVIRONMENTS = [
        'local',
        'testing',
    ];

    public function run(): void
    {
        if (! $this->isAllowedEnvironment()) {
            $this->warn('UserSeeder skipped. Only available in local/testing environments.');

            return;
        }

        $user = config('seeding.test_user');

        if (empty($user['email']) || empty($user['password'])) {
            $this->warn('UserSeeder skipped. SEED_TEST_USER_EMAIL and SEED_TEST_USER_PASSWORD must be set in .env.');

            return;
        }

        $useCase = app(UserCreateUseCase::class);

        try {
            $useCase->execute(
                new UserCreateCommand(
                    email: $user['email'],
                    password: $user['password'],
                    name: $user['name'] ?? 'Test User',
                )
            );
        } catch (UserAlreadyExistsException) {
            $this->warn('UserSeeder skipped. User with the specified email already exists.');
        }
    }

    private function isAllowedEnvironment(): bool
    {
        return app()->environment(self::ALLOWED_ENVIRONMENTS);
    }

    private function warn(string $message): void
    {
        $this->command?->warn($message);
    }
}
