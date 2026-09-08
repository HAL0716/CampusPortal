<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Enrollment\Entities\Enrollment;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Enrollment\Exceptions\EnrollmentAlreadyExistsException;
use App\Domain\Enrollment\Exceptions\EnrollmentNotFoundException;
use App\Infrastructure\Repositories\EloquentEnrollmentRepository;
use App\Models\CourseOffering;
use App\Models\Enrollment as EnrollmentModel;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\EnrollmentTestHelper;
use Tests\TestCase;

final class EloquentEnrollmentRepositoryTest extends TestCase
{
    use EnrollmentTestHelper;
    use RefreshDatabase;

    private function repository(): EloquentEnrollmentRepository
    {
        return app(EloquentEnrollmentRepository::class);
    }

    public function test_save_creates_enrollment(): void
    {
        $enrollment = $this->createEnrollment(
            studentId: Student::factory()->create()->id,
            courseOfferingId: CourseOffering::factory()->create()->id,
        );

        $result = $this->repository()->save($enrollment);

        self::assertInstanceOf(Enrollment::class, $result);
        self::assertNotNull($result->id());
        self::assertSame($enrollment->studentId()->value(), $result->studentId()->value());
        self::assertSame($enrollment->courseOfferingId()->value(), $result->courseOfferingId()->value());
        self::assertSame($enrollment->status(), $result->status());

        $this->assertDatabaseHas('enrollments', [
            'id' => $result->requireId()->value(),
            'student_id' => $enrollment->studentId()->value(),
            'course_offering_id' => $enrollment->courseOfferingId()->value(),
            'status' => $enrollment->status(),
        ]);
    }

    public function test_save_updates_existing_enrollment(): void
    {
        $model = EnrollmentModel::factory()->status(EnrollmentStatus::ENROLLED)->create();

        $enrollment = $this->reconstructEnrollment(
            id: $model->id,
            studentId: $model->student_id,
            courseOfferingId: $model->course_offering_id,
            status: EnrollmentStatus::DROPPED,
        );

        $result = $this->repository()->save($enrollment);

        self::assertSame($enrollment->requireId()->value(), $result->requireId()->value());
        self::assertSame($enrollment->status(), $result->status());

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->requireId()->value(),
            'status' => $enrollment->status(),
        ]);
    }

    public function test_save_throws_exception_when_updating_nonexistent_enrollment(): void
    {
        $enrollment = $this->reconstructEnrollment(
            id: 999999,
            studentId: Student::factory()->create()->id,
            courseOfferingId: CourseOffering::factory()->create()->id,
        );

        self::expectException(EnrollmentNotFoundException::class);

        $this->repository()->save($enrollment);
    }

    public function test_save_throws_exception_when_enrollment_already_exists(): void
    {
        $model = EnrollmentModel::factory()->status(EnrollmentStatus::ENROLLED)->create();

        $enrollment = $this->createEnrollment(
            studentId: $model->student_id,
            courseOfferingId: $model->course_offering_id,
        );

        self::expectException(EnrollmentAlreadyExistsException::class);

        $this->repository()->save($enrollment);
    }

    public function test_find_by_id_returns_enrollment(): void
    {
        $model = EnrollmentModel::factory()->create();

        $result = $this->repository()->findById($this->enrollmentId($model->id));

        self::assertInstanceOf(Enrollment::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_find_by_id_returns_null_when_enrollment_does_not_exist(): void
    {
        self::assertNull($this->repository()->findById($this->enrollmentId(999999)));
    }

    public function test_get_by_id_returns_enrollment(): void
    {
        $model = EnrollmentModel::factory()->create();

        $result = $this->repository()->getById($this->enrollmentId($model->id));

        self::assertInstanceOf(Enrollment::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_get_by_id_throws_exception_when_enrollment_does_not_exist(): void
    {
        self::expectException(EnrollmentNotFoundException::class);

        $this->repository()->getById($this->enrollmentId(999999));
    }

    public function test_find_by_student_and_course_offering_returns_enrollment(): void
    {
        $model = EnrollmentModel::factory()->create();

        $result = $this->repository()->findByStudentAndCourseOffering(
            $this->studentId($model->student_id),
            $this->courseOfferingId($model->course_offering_id),
        );

        self::assertInstanceOf(Enrollment::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_find_by_student_and_course_offering_returns_null_when_enrollment_does_not_exist(): void
    {
        $student = Student::factory()->create();
        $offering = CourseOffering::factory()->create();

        self::assertNull(
            $this->repository()->findByStudentAndCourseOffering(
                $this->studentId($student->id),
                $this->courseOfferingId($offering->id),
            ),
        );
    }

    public function test_get_by_student_and_course_offering_returns_enrollment(): void
    {
        $model = EnrollmentModel::factory()->create();

        $result = $this->repository()->getByStudentAndCourseOffering(
            $this->studentId($model->student_id),
            $this->courseOfferingId($model->course_offering_id),
        );

        self::assertInstanceOf(Enrollment::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_get_by_student_and_course_offering_throws_exception_when_enrollment_does_not_exist(): void
    {
        $student = Student::factory()->create();
        $offering = CourseOffering::factory()->create();

        self::expectException(EnrollmentNotFoundException::class);

        $this->repository()->getByStudentAndCourseOffering(
            $this->studentId($student->id),
            $this->courseOfferingId($offering->id),
        );
    }
}
