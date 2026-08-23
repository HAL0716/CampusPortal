<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Models\CourseOffering;
use App\Models\Material;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class MaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_material(): void
    {
        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([
                PermissionType::MaterialView,
            ])->create(),
        ])->create();

        $material = Material::factory()->create();

        $this->actingAs($user)
            ->get(route('materials.show', $material))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Material/Show')
                ->has('material')
                ->where('material.id', $material->id),
            );
    }

    public function test_cannot_view_material_without_permission(): void
    {
        $user = User::factory()->create();

        $material = Material::factory()->create();

        $this->actingAs($user)
            ->get(route('materials.show', $material))
            ->assertForbidden();
    }

    public function test_can_upload_material(): void
    {
        Storage::fake(config('filesystems.default'));

        [$user, $offering] = $this->teacherOffering();

        $response = $this->actingAs($user)->post(
            route('course-offerings.materials.store', $offering),
            $this->data(),
        );

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('materials', [
            'course_offering_id' => $offering->id,
            'title' => '第1回講義資料',
            'description' => '講義資料です。',
        ]);

        $material = $offering->materials()->first();

        $this->assertTrue(Storage::disk(config('filesystems.default'))->exists($material->file_path));
    }

    public function test_returns_error_when_unauthorized(): void
    {
        Storage::fake(config('filesystems.default'));

        $user = $this->userWithPermission(
            PermissionType::CourseOfferingMaterialCreate
        );
        $offering = CourseOffering::factory()->create();

        $response = $this->actingAs($user)->post(
            route('course-offerings.materials.store', $offering),
            $this->data(),
        );

        $response->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('materials', [
            'course_offering_id' => $offering->id,
        ]);
    }

    public function test_returns_error_when_validation_fails(): void
    {
        [$user, $offering] = $this->teacherOffering();

        $response = $this->actingAs($user)->post(
            route('course-offerings.materials.store', $offering),
            [],
        );

        $response->assertSessionHasErrors(['title']);
    }

    private function userWithPermission(PermissionType $permissionType): User
    {
        $user = User::factory()->create();

        $permission = Permission::factory()->create([
            'name' => $permissionType->value,
        ]);

        $role = Role::factory()->create();
        $role->permissions()->attach($permission);

        $user->roles()->attach($role);

        return $user;
    }

    /**
     * @return array{User, CourseOffering}
     */
    private function teacherOffering(): array
    {
        $user = $this->userWithPermission(
            PermissionType::CourseOfferingMaterialCreate
        );

        $teacher = Teacher::factory()->for($user)->create();

        return [
            $user,
            CourseOffering::factory()->forTeacher($teacher)->create(),
        ];
    }

    private function data(): array
    {
        return [
            'title' => '第1回講義資料',
            'description' => '講義資料です。',
            'publishDate' => now()->toDateString(),
            'file' => UploadedFile::fake()->create('test.pdf', 100, 'application/pdf'),
        ];
    }
}
