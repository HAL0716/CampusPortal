<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\CourseOffering\CourseOfferingId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Enrollment\Exceptions\EnrollmentAlreadyExistsException;
use App\Domain\Student\StudentId;
use App\Infrastructure\Repositories\EnrollmentRepository;
use App\Models\CourseOffering;
use App\Models\Enrollment as EnrollmentModel;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EnrollmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EnrollmentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EnrollmentRepository::class);
    }

    public function test_can_save_new_enrollment(): void
    {
        $student = Student::factory()->create();
        $offering = CourseOffering::factory()->create();

        $saved = $this->repository->save(
            Enrollment::create(
                new StudentId($student->id),
                new CourseOfferingId($offering->id),
            )
        );

        self::assertInstanceOf(Enrollment::class, $saved);
        self::assertNotNull($saved->id());
        self::assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_offering_id' => $offering->id,
            'status' => EnrollmentStatus::ENROLLED->value,
        ]);
    }

    public function test_can_update_existing_enrollment(): void
    {
        $enrollment = EnrollmentModel::factory()->create();

        $saved = $this->repository->save(
            Enrollment::reconstruct(
                new EnrollmentId($enrollment->id),
                new StudentId($enrollment->student_id),
                new CourseOfferingId($enrollment->course_offering_id),
                EnrollmentStatus::COMPLETED,
            )
        );

        self::assertSame($enrollment->id, $saved->id()->value());
        self::assertSame(EnrollmentStatus::COMPLETED, $saved->status());
    }

    public function test_throws_exception_when_duplicate_enrollment(): void
    {
        $student = Student::factory()->create();
        $offering = CourseOffering::factory()->create();

        EnrollmentModel::create([
            'student_id' => $student->id,
            'course_offering_id' => $offering->id,
            'status' => EnrollmentStatus::ENROLLED->value,
        ]);

        self::expectException(EnrollmentAlreadyExistsException::class);

        $this->repository->save(
            Enrollment::create(
                new StudentId($student->id),
                new CourseOfferingId($offering->id),
            )
        );
    }

    public function test_can_find_enrollment_by_id(): void
    {
        $model = EnrollmentModel::factory()->create();

        $result = $this->repository->findById(new EnrollmentId($model->id));

        self::assertInstanceOf(Enrollment::class, $result);
        self::assertSame($model->id, $result->id()->value());
    }

    public function test_returns_null_when_enrollment_not_found_by_id(): void
    {
        $result = $this->repository->findById(new EnrollmentId(999999));

        self::assertNull($result);
    }

    public function test_can_find_enrollment_by_student_and_course_offering(): void
    {
        $model = EnrollmentModel::factory()->create();

        $result = $this->repository->find(
            new StudentId($model->student_id),
            new CourseOfferingId($model->course_offering_id),
        );

        self::assertInstanceOf(Enrollment::class, $result);
        self::assertSame($model->id, $result->id()->value());
    }

    public function test_returns_null_when_enrollment_not_found_by_student_and_course_offering(): void
    {
        $result = $this->repository->find(
            new StudentId(999999),
            new CourseOfferingId(999999),
        );

        self::assertNull($result);
    }
}
