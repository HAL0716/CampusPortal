<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\CourseOffering\DTOs\CourseOfferingDetailDTO;
use App\Application\Contexts\CourseOffering\DTOs\CourseOfferingDTO;
use App\Application\Contexts\CourseOffering\DTOs\MaterialDTO;
use App\Application\Contexts\CourseOffering\Enums\CourseOfferingStatus;
use App\Domain\CourseOffering\Exceptions\CourseOfferingNotFoundException;
use App\Domain\CourseOffering\ValueObjects\CourseOfferingId;
use App\Domain\Enrollment\Enums\EnrollmentStatus;
use App\Domain\Semester\ValueObjects\SemesterId;
use App\Domain\Student\ValueObjects\StudentId;
use App\Domain\Teacher\ValueObjects\TeacherId;
use App\Infrastructure\QueryServices\EloquentCourseOfferingQueryService;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Clock\UseClock;
use Tests\TestCase;

final class EloquentCourseOfferingQueryServiceTest extends TestCase
{
    use RefreshDatabase;
    use UseClock;

    private Semester $semester;

    private CarbonImmutable $now;

    protected function setUp(): void
    {
        parent::setUp();

        $this->semester = Semester::factory()->create();

        $startDate = CarbonImmutable::instance($this->semester->start_date);
        $endDate = CarbonImmutable::instance($this->semester->end_date);

        $this->now = $startDate->addDays(
            intdiv($startDate->diffInDays($endDate), 2),
        );

        $this->useClock($this->now);
    }

    private function queryService(): EloquentCourseOfferingQueryService
    {
        return app(EloquentCourseOfferingQueryService::class);
    }

    public function test_find_by_semester_returns_none_status_without_member(): void
    {
        $offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        $result = $this->queryService()->findBySemester(
            new SemesterId($this->semester->id),
        );

        self::assertCount(1, $result);
        self::assertInstanceOf(CourseOfferingDTO::class, $result[0]);
        self::assertSame($offering->id, $result[0]->id);
        self::assertSame($offering->course->name, $result[0]->name);
        self::assertSame($offering->course->description, $result[0]->description);
        self::assertSame(CourseOfferingStatus::NONE, $result[0]->status);
    }

    public function test_find_by_semester_returns_enrolled_status_for_student(): void
    {
        $offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        $student = Student::factory()->create();

        Enrollment::factory()
            ->for($student)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $result = $this->queryService()->findBySemester(
            new SemesterId($this->semester->id),
            new StudentId($student->id),
        );

        self::assertCount(1, $result);
        self::assertSame($offering->id, $result[0]->id);
        self::assertSame(CourseOfferingStatus::ENROLLED, $result[0]->status);
    }

    public function test_find_by_semester_returns_none_when_student_is_not_enrolled(): void
    {
        $offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        $student = Student::factory()->create();

        $result = $this->queryService()->findBySemester(
            new SemesterId($this->semester->id),
            new StudentId($student->id),
        );

        self::assertCount(1, $result);
        self::assertSame($offering->id, $result[0]->id);
        self::assertSame(CourseOfferingStatus::NONE, $result[0]->status);
    }

    public function test_find_by_semester_returns_teaching_for_teacher(): void
    {
        $teacher = Teacher::factory()->create();

        $offering = CourseOffering::factory()
            ->forTeachers([$teacher])
            ->for($this->semester)
            ->create();

        $result = $this->queryService()->findBySemester(
            new SemesterId($this->semester->id),
            new TeacherId($teacher->id),
        );

        self::assertCount(1, $result);
        self::assertSame($offering->id, $result[0]->id);
        self::assertSame(CourseOfferingStatus::TEACHING, $result[0]->status);
    }

    public function test_find_by_semester_returns_not_teaching_for_teacher(): void
    {
        $teacher = Teacher::factory()->create();

        $offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        $result = $this->queryService()->findBySemester(
            new SemesterId($this->semester->id),
            new TeacherId($teacher->id),
        );

        self::assertCount(1, $result);
        self::assertSame($offering->id, $result[0]->id);
        self::assertSame(CourseOfferingStatus::NOT_TEACHING, $result[0]->status);
    }

    public function test_get_detail_returns_detail_without_member(): void
    {
        $teacher = Teacher::factory()->create();

        $offering = CourseOffering::factory()
            ->forTeachers([$teacher])
            ->for($this->semester)
            ->create();

        $publishedMaterial = Material::factory()
            ->for($offering)
            ->create([
                'publish_date' => $this->now->subHour(),
            ]);

        $futureMaterial = Material::factory()
            ->for($offering)
            ->create([
                'publish_date' => $this->now->addHour(),
            ]);

        $nullPublishDateMaterial = Material::factory()
            ->for($offering)
            ->create([
                'publish_date' => null,
            ]);

        $result = $this->queryService()->getDetail(
            new CourseOfferingId($offering->id),
        );

        self::assertInstanceOf(CourseOfferingDetailDTO::class, $result);
        self::assertSame($offering->id, $result->id);
        self::assertSame($offering->course->name, $result->name);
        self::assertSame($offering->course->description, $result->description);
        self::assertSame(CourseOfferingStatus::NONE, $result->status);

        self::assertCount(1, $result->teachers);
        self::assertCount(2, $result->materials);
        self::assertContainsOnlyInstancesOf(MaterialDTO::class, $result->materials);

        $materialIds = array_map(fn (MaterialDTO $material): int => $material->id, $result->materials);

        self::assertSame(
            [
                $publishedMaterial->id,
                $nullPublishDateMaterial->id,
            ],
            $materialIds,
        );

        self::assertNotContains(
            $futureMaterial->id,
            $materialIds,
        );
    }

    public function test_get_detail_returns_student_status(): void
    {
        $student = Student::factory()->create();

        $offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        Enrollment::factory()
            ->for($student)
            ->for($offering)
            ->status(EnrollmentStatus::ENROLLED)
            ->create();

        $result = $this->queryService()->getDetail(
            new CourseOfferingId($offering->id),
            new StudentId($student->id),
        );

        self::assertSame(CourseOfferingStatus::ENROLLED, $result->status);
    }

    public function test_get_detail_returns_none_for_student_without_enrollment(): void
    {
        $student = Student::factory()->create();

        $offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        $result = $this->queryService()->getDetail(
            new CourseOfferingId($offering->id),
            new StudentId($student->id),
        );

        self::assertSame(CourseOfferingStatus::NONE, $result->status);
    }

    public function test_get_detail_returns_teaching_for_teacher(): void
    {
        $teacher = Teacher::factory()->create();

        $offering = CourseOffering::factory()
            ->forTeachers([$teacher])
            ->for($this->semester)
            ->create();

        $result = $this->queryService()->getDetail(
            new CourseOfferingId($offering->id),
            new TeacherId($teacher->id),
        );

        self::assertSame(CourseOfferingStatus::TEACHING, $result->status);
    }

    public function test_get_detail_returns_not_teaching_for_teacher(): void
    {
        $teacher = Teacher::factory()->create();

        $offering = CourseOffering::factory()
            ->for($this->semester)
            ->create();

        $result = $this->queryService()->getDetail(
            new CourseOfferingId($offering->id),
            new TeacherId($teacher->id),
        );

        self::assertSame(CourseOfferingStatus::NOT_TEACHING, $result->status);
    }

    public function test_get_detail_throws_exception_when_course_offering_does_not_exist(): void
    {
        $this->expectException(CourseOfferingNotFoundException::class);

        $this->queryService()->getDetail(
            new CourseOfferingId(999999),
        );
    }
}
