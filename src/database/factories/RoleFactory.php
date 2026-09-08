<?php

namespace Database\Factories;

use App\Domain\Role\Enums\RoleType;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(RoleType::cases()),
        ];
    }

    public function withName(RoleType $name): static
    {
        return $this->state(fn (): array => ['name' => $name]);
    }

    /**
     * @param  array<int, Permission>  $permissions
     */
    public function withPermissions(array $permissions): static
    {
        return $this->afterCreating(function (Role $role) use ($permissions): void {
            $role->permissions()->sync(
                array_map(
                    fn (Permission $permission): int => $permission->id,
                    $permissions,
                ),
            );
        });
    }
}
