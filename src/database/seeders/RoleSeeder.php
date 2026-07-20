<?php

namespace Database\Seeders;

use App\Domain\Role\RoleType;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RoleType::cases() as $role) {
            Role::firstOrCreate([
                'name' => $role,
            ]);
        }
    }
}
