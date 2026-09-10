<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Domain\Role\Enums\RoleType;
use App\Domain\Student\Enums\StudentStatus;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }

    public function test_can_view_students(): void
    {
        $admin = $this->createAdmin();
        $student = Student::factory()->create();

        $this->actingAs($admin)
            ->get(route('students.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Student/Index')
                ->has('students')
                ->where('students.0.studentNumber', $student->student_number)
            );
    }

    public function test_cannot_view_students_without_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('students.index'))
            ->assertForbidden();
    }

    public function test_can_view_student_create_form(): void
    {
        $admin = $this->createAdmin();
        $department = Department::factory()->create();

        $this->actingAs($admin)
            ->get(route('students.create'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Student/Create')
                ->has('departments')
                ->where('departments.0.id', $department->id)
                ->where('departments.0.name', $department->name)
            );
    }

    public function test_cannot_view_student_create_form_without_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('students.create'))
            ->assertForbidden();
    }

    public function test_can_create_student(): void
    {
        $admin = $this->createAdmin();
        $data = $this->studentData();

        $this->actingAs($admin)
            ->post(route('students.store'), $data)
            ->assertRedirect(route('students.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $this->assertDatabaseHas('students', [
            'student_number' => $data['studentNumber'],
            'department_id' => $data['departmentId'],
            'status' => StudentStatus::ACTIVE,
        ]);
    }

    public function test_cannot_create_student_without_permission(): void
    {
        $user = User::factory()->create();
        $data = $this->studentData();

        $this->actingAs($user)
            ->post(route('students.store'), $data)
            ->assertForbidden();
    }

    public function test_can_view_student_detail(): void
    {
        $admin = $this->createAdmin();
        $student = Student::factory()->create();

        $this->actingAs($admin)
            ->get(route('students.show', $student->id))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Student/Show')
                ->has('student')
                ->where('student.studentNumber', $student->student_number)
            );
    }

    public function test_cannot_view_student_detail_without_permission(): void
    {
        $user = User::factory()->create();
        $student = Student::factory()->create();

        $this->actingAs($user)
            ->get(route('students.show', $student->id))
            ->assertForbidden();
    }

    private function createAdmin(): User
    {
        $role = Role::query()
            ->where('name', RoleType::ADMIN)
            ->firstOrFail();

        $permission = Permission::query()
            ->where('name', PermissionType::StudentManage)
            ->firstOrFail();

        $role->permissions()->syncWithoutDetaching($permission);

        return User::factory()
            ->withRoles([$role])
            ->create();
    }

    private function studentData(array $overrides = []): array
    {
        return array_merge([
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'password' => 'password123',
            'studentNumber' => '000001',
            'departmentId' => Department::factory()->create()->id,
        ], $overrides);
    }
}
