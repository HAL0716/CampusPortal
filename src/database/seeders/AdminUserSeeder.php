<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => config('admin.email')],
            [
                'name' => config('admin.name'),
                'login_id' => config('admin.login_id'),
                'password' => Hash::make(config('admin.password')),
                'role' => UserRole::ADMIN,
            ]
        );
    }
}
