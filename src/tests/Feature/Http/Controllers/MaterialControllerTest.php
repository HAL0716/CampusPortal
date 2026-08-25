<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Permission\Enums\PermissionType;
use App\Models\CourseOffering;
use App\Models\Material;
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
            Role::factory()->withPermissions([PermissionType::MaterialView])->create(),
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

    public function test_can_store_material(): void
    {
        Storage::fake(config('filesystems.default'));

        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::MaterialCreate])->create(),
        ])->create();

        $offering = CourseOffering::factory()
            ->forTeacher(Teacher::factory()->for($user)->create())
            ->create();

        $this->actingAs($user)
            ->post(route('course-offerings.materials.store', $offering), $this->data())
            ->assertRedirect(route('course-offerings.show', $offering));

        $material = $offering->materials()->firstOrFail();

        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'course_offering_id' => $offering->id,
            'title' => 'テスト資料',
        ]);

        Storage::assertExists($material->file_path);
    }

    public function test_cannot_store_material_without_permission(): void
    {
        Storage::fake(config('filesystems.default'));

        $user = User::factory()->create();

        $offering = CourseOffering::factory()
            ->forTeacher(Teacher::factory()->for($user)->create())
            ->create();

        $this->actingAs($user)
            ->post(route('course-offerings.materials.store', $offering), $this->data())
            ->assertForbidden();

        $this->assertDatabaseMissing('materials', [
            'course_offering_id' => $offering->id,
        ]);
    }

    public function test_can_download_material(): void
    {
        Storage::fake(config('filesystems.default'));

        $user = User::factory()->withRoles([
            Role::factory()->withPermissions([PermissionType::MaterialView])->create(),
        ])->create();

        $material = Material::factory()->create(['file_path' => 'materials/test.pdf']);

        Storage::put($material->file_path, 'test');

        $this->actingAs($user)
            ->get(route('materials.download', $material))
            ->assertOk()
            ->assertDownload();
    }

    public function test_cannot_download_material_without_permission(): void
    {
        Storage::fake(config('filesystems.default'));

        $user = User::factory()->create();
        $material = Material::factory()->create(['file_path' => 'materials/test.pdf']);

        Storage::put($material->file_path, 'test');

        $this->actingAs($user)
            ->get(route('materials.download', $material))
            ->assertForbidden();
    }

    private function data(): array
    {
        return [
            'title' => 'テスト資料',
            'file' => UploadedFile::fake()->create('テスト資料.pdf', 100, 'application/pdf'),
        ];
    }
}
