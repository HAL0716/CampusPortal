<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CourseOffering;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class MaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_material(): void
    {
        Storage::fake('public');

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
        ]);
    }

    public function test_returns_error_when_unauthorized(): void
    {
        $user = User::factory()->create();
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
            []
        );

        $response->assertSessionHasErrors(['title']);
    }

    /**
     * @return array{User, CourseOffering}
     */
    private function teacherOffering(): array
    {
        $user = User::factory()->create();
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
            'publish_date' => now()->toDateString(),
            'file' => UploadedFile::fake()->create('test.pdf', 100, 'application/pdf'),
        ];
    }
}
