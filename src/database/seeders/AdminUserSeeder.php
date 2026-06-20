<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = [
            'name' => config('admin.name'),
            'login_id' => config('admin.login_id'),
            'password' => config('admin.password'),
        ];

        if (empty($admin['name']) || empty($admin['login_id']) || empty($admin['password'])) {
            throw new InvalidArgumentException(
                'Please set ADMIN_LOGIN_ID, ADMIN_NAME, and ADMIN_PASSWORD.'
            );
        }

        User::firstOrCreate(
            [
                'login_id' => $admin['login_id'],
            ],
            User::factory()
                ->admin()
                ->make($admin)
                ->getAttributes()
        );
    }
}
