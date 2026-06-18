<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherAppointTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_create_page_can_be_rendered(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('teachers.appoint'))
            ->assertOk();
    }

    public function test_admin_can_create_teacher(): void
    {
        $admin = User::factory()->admin()->create();
        $department = Department::factory()->create();

        $this->actingAs($admin)
            ->post(route('teachers.appoint'), [
                'name' => 'Test Teacher',
                'email' => 'teacher@example.com',
                'department_id' => $department->id,
            ])
            ->assertRedirect(route('teachers.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'teacher@example.com',
            'role' => UserRole::TEACHER->value,
        ]);

        $this->assertDatabaseHas('teachers', [
            'department_id' => $department->id,
        ]);
    }

    public function test_teacher_creation_requires_validation(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('teachers.appoint'), [])
            ->assertSessionHasErrors([
                'name',
                'email',
                'department_id',
            ]);
    }

    public function test_non_admin_cannot_create_teacher(): void
    {
        $user = User::factory()->teacher()->create();
        $department = Department::factory()->create();

        $this->actingAs($user)
            ->post(route('teachers.appoint'), [
                'name' => 'Test Teacher',
                'email' => 'teacher@example.com',
                'department_id' => $department->id,
            ])
            ->assertForbidden();
    }
}
