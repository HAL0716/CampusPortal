<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\CourseOffering\Entities\CourseOffering;
use App\Domain\CourseOffering\Exceptions\CourseOfferingAlreadyExistsException;
use App\Domain\CourseOffering\Exceptions\CourseOfferingNotFoundException;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Infrastructure\Repositories\EloquentCourseOfferingRepository;
use App\Models\Course;
use App\Models\CourseOffering as CourseOfferingModel;
use App\Models\Position;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\CourseOfferingTestHelper;
use Tests\TestCase;

final class EloquentCourseOfferingRepositoryTest extends TestCase
{
    use CourseOfferingTestHelper;
    use RefreshDatabase;

    private function repository(): EloquentCourseOfferingRepository
    {
        return app(EloquentCourseOfferingRepository::class);
    }

    public function test_save_creates_course_offering(): void
    {
        $courseOffering = $this->createCourseOffering(
            semesterId: Semester::factory()->create()->id,
            courseId: Course::factory()->create()->id,
        );

        $result = $this->repository()->save($courseOffering);

        self::assertInstanceOf(CourseOffering::class, $result);
        self::assertNotNull($result->id());
        self::assertSame($courseOffering->courseId()->value(), $result->courseId()->value());
        self::assertSame($courseOffering->semesterId()->value(), $result->semesterId()->value());

        $this->assertDatabaseHas('course_offerings', [
            'id' => $result->requireId()->value(),
            'course_id' => $result->courseId()->value(),
            'semester_id' => $result->semesterId()->value(),
        ]);
    }

    public function test_save_updates_existing_course_offering(): void
    {
        $model = CourseOfferingModel::factory()->create();

        $courseOffering = $this->reconstructCourseOffering(id: $model->id);

        $result = $this->repository()->save($courseOffering);

        self::assertInstanceOf(CourseOffering::class, $result);
        self::assertSame($model->id, $result->id()->value());
        self::assertSame($courseOffering->courseId()->value(), $result->courseId()->value());
        self::assertSame($courseOffering->semesterId()->value(), $result->semesterId()->value());

        $this->assertDatabaseHas('course_offerings', [
            'id' => $result->requireId()->value(),
            'course_id' => $result->courseId()->value(),
            'semester_id' => $result->semesterId()->value(),
        ]);
    }

    public function test_save_throws_exception_when_updating_non_existing_course_offering(): void
    {
        $courseOffering = $this->reconstructCourseOffering(id: 999999);

        $this->expectException(CourseOfferingNotFoundException::class);

        $this->repository()->save($courseOffering);
    }

    public function test_save_throws_exception_when_already_exists(): void
    {
        $model = CourseOfferingModel::factory()->create();

        $courseOffering = $this->createCourseOffering(
            courseId: $this->courseId($model->course_id)->value(),
            semesterId: $this->semesterId($model->semester_id)->value(),
        );

        $this->expectException(CourseOfferingAlreadyExistsException::class);

        $this->repository()->save($courseOffering);
    }

    public function test_find_by_id_returns_course_offering_entity(): void
    {
        $model = CourseOfferingModel::factory()->create();

        $result = $this->repository()->findById($this->courseOfferingId($model->id));

        self::assertInstanceOf(CourseOffering::class, $result);
        self::assertSame($model->id, $result->id()->value());
        self::assertSame($model->semester_id, $result->semesterId()->value());
        self::assertSame($model->course_id, $result->courseId()->value());
        self::assertCount(0, $result->teacherIds());
    }

    public function test_find_by_id_returns_course_offering_entity_with_teacher_ids(): void
    {
        $teacher = Teacher::factory()->create();

        $model = CourseOfferingModel::factory()->forTeachers([$teacher])->create();

        $result = $this->repository()->findById($this->courseOfferingId($model->id));

        self::assertSame([$teacher->id], array_map(
            fn (TeacherId $teacherId): int => $teacherId->value(),
            $result->teacherIds(),
        ));
    }

    public function test_find_by_id_returns_course_offering_entity_with_multiple_teacher_ids(): void
    {
        $position = Position::factory()->create();

        $teachers = [
            Teacher::factory()->for($position)->create(),
            Teacher::factory()->for($position)->create(),
        ];

        $model = CourseOfferingModel::factory()
            ->forTeachers($teachers)
            ->create();

        $result = $this->repository()->findById($this->courseOfferingId($model->id));

        self::assertSame(
            array_map(fn (Teacher $teacher): int => $teacher->id, $teachers),
            array_map(fn (TeacherId $teacherId): int => $teacherId->value(), $result->teacherIds()),
        );
    }

    public function test_find_by_id_returns_null_when_course_offering_does_not_exist(): void
    {
        self::assertNull($this->repository()->findById($this->courseOfferingId(999999)));
    }
}
