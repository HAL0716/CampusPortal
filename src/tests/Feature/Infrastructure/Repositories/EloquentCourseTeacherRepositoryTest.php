<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Course\Repositories\CourseTeacherRepository;
use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Infrastructure\Repositories\EloquentCourseTeacherRepository;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentCourseTeacherRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CourseTeacherRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(EloquentCourseTeacherRepository::class);
    }

    public function test_exists_returns_true_when_relation_exists(): void
    {
        $teacher = Teacher::factory()->create();
        $course = Course::factory()
            ->forTeachers([$teacher])
            ->create();

        self::assertTrue(
            $this->repository->exists(
                new CourseId($course->id),
                new TeacherId($teacher->id),
            ),
        );
    }

    public function test_exists_returns_false_when_relation_does_not_exist(): void
    {
        $teacher = Teacher::factory()->create();
        $course = Course::factory()->create();

        self::assertFalse(
            $this->repository->exists(
                new CourseId($course->id),
                new TeacherId($teacher->id),
            ),
        );
    }
}
