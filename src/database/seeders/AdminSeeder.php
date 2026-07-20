<?php

namespace Database\Seeders;

use App\Domain\Role\RoleType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = config('admin', []);

        if (empty($admin['email']) || empty($admin['password'])
        ) {
            $this->warn('AdminSeeder skipped. ADMIN_EMAIL and ADMIN_PASSWORD must be set in .env.');

            return;
        }

        $user = User::firstOrCreate([
            'email' => $admin['email'],
        ], [
            'name' => $admin['name'] ?? 'Admin User',
            'password' => Hash::make($admin['password']),
        ]);

        $role = Role::where(
            'name',
            RoleType::ADMIN
        )->firstOrFail();

        $user->roles()->syncWithoutDetaching([
            $role->id,
        ]);
    }

    private function warn(string $message): void
    {
        $this->command?->warn($message);
    }
}
