<?php

namespace Database\Seeders;

use App\Domain\Permission\PermissionType;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
