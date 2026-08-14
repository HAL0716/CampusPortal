<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Infrastructure\Repositories\CourseOfferingRepository;
use App\Models\CourseOffering as CourseOfferingModel;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CourseOfferingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CourseOfferingRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(CourseOfferingRepository::class);
    }

    public function test_can_find_course_offering_by_id(): void
    {
        $offering = CourseOfferingModel::factory()->create();
        $teacher = Teacher::factory()->create();

        $offering->course->teachers()->attach($teacher->id);

        $result = $this->repository->findById(new CourseOfferingId($offering->id));

        self::assertInstanceOf(CourseOffering::class, $result);
        self::assertSame($offering->id, $result->id()->value());
        self::assertSame($offering->semester_id, $result->semesterId()->value());
        self::assertSame($offering->course_id, $result->courseId()->value());
        self::assertCount(1, $result->teacherIds());
        self::assertSame($teacher->id, $result->teacherIds()[0]->value());
    }

    public function test_can_find_course_offering_without_teachers(): void
    {
        $model = CourseOfferingModel::factory()->create();

        $result = $this->repository->findById(new CourseOfferingId($model->id));

        self::assertInstanceOf(CourseOffering::class, $result);
        self::assertSame($model->id, $result->id()->value());
    }

    public function test_returns_null_when_course_offering_not_found(): void
    {
        $result = $this->repository->findById(new CourseOfferingId(999999));

        self::assertNull($result);
    }
}
