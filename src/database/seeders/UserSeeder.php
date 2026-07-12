<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        User::updateOrCreate(
            [
                'email' => $user['email'],
            ],
            [
                'name' => $user['name'] ?? 'Test User',
                'password' => Hash::make($user['password']),
            ]
        );
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
