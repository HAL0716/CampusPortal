<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Academic\Enums\Term;
use App\Domain\Semester\Entities\Semester;
use App\Domain\Semester\Exceptions\SemesterAlreadyExistsException;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Domain\Semester\ValueObjects\AcademicYear;
use App\Infrastructure\Repositories\EloquentSemesterRepository;
use App\Models\Semester as SemesterModel;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\SemesterTestHelper;
use Tests\TestCase;

final class EloquentSemesterRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use SemesterTestHelper;

    private function repository(): EloquentSemesterRepository
    {
        return app(EloquentSemesterRepository::class);
    }

    public function test_save_creates_semester(): void
    {
        $semester = $this->createSemester();

        $result = $this->repository()->save($semester);

        self::assertInstanceOf(Semester::class, $result);
        self::assertNotNull($result->id());
        self::assertSame($semester->academicYear()->value(), $result->academicYear()->value());
        self::assertSame($semester->term()->value, $result->term()->value);
        self::assertSame($semester->startDate()->format('Y-m-d'), $result->startDate()->format('Y-m-d'));
        self::assertSame($semester->endDate()->format('Y-m-d'), $result->endDate()->format('Y-m-d'));

        $this->assertDatabaseHas('semesters', [
            'id' => $result->requireId()->value(),
            'academic_year' => $result->academicYear()->value(),
            'term' => $result->term()->value,
        ]);
    }

    public function test_save_updates_existing_semester(): void
    {
        $model = SemesterModel::factory()->create();

        $semester = $this->reconstructSemester(
            id: $model->id,
            academicYear: new AcademicYear($model->academic_year),
            term: $model->term->next(),
        );

        $result = $this->repository()->save($semester);

        self::assertSame($semester->requireId()->value(), $result->requireId()->value());
        self::assertSame($semester->term()->value, $result->term()->value);

        $this->assertDatabaseHas('semesters', [
            'id' => $result->requireId()->value(),
            'term' => $result->term()->value,
        ]);
    }

    public function test_throws_exception_when_updating_nonexistent_semester(): void
    {
        $semester = $this->reconstructSemester(
            id: 9999,
        );

        self::expectException(SemesterNotFoundException::class);

        $this->repository()->save($semester);
    }

    public function test_throws_exception_when_already_exists(): void
    {
        $model = SemesterModel::factory()->create();

        $semester = $this->createSemester(
            academicYear: new AcademicYear($model->academic_year),
            term: $model->term,
        );

        self::expectException(SemesterAlreadyExistsException::class);

        $this->repository()->save($semester);
    }

    public function test_get_by_date_returns_semester_when_date_is_start_date(): void
    {
        $model = SemesterModel::factory()->create();

        $result = $this->repository()->getByDate(CarbonImmutable::parse($model->start_date));

        self::assertInstanceOf(Semester::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
        self::assertSame($model->academic_year, $result->academicYear()->value());
        self::assertSame($model->term->value, $result->term()->value);
        self::assertSame($model->start_date->format('Y-m-d'), $result->startDate()->format('Y-m-d'));
        self::assertSame($model->end_date->format('Y-m-d'), $result->endDate()->format('Y-m-d'));
    }

    public function test_get_by_date_returns_semester_when_date_is_end_date(): void
    {
        $model = SemesterModel::factory()->create();

        $result = $this->repository()->getByDate(CarbonImmutable::parse($model->end_date));

        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_get_by_date_returns_semester_when_date_is_between_start_and_end_date(): void
    {
        $model = SemesterModel::factory()->create();

        $date = CarbonImmutable::parse($model->start_date)
            ->addDays(
                CarbonImmutable::parse($model->start_date)
                    ->diffInDays(CarbonImmutable::parse($model->end_date)) / 2,
            );

        $result = $this->repository()->getByDate($date);

        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_get_by_date_throws_exception_when_date_is_before_start_date(): void
    {
        $model = SemesterModel::factory()->create();

        self::expectException(SemesterNotFoundException::class);

        $this->repository()->getByDate(CarbonImmutable::parse($model->start_date)->subDay());
    }

    public function test_get_by_date_throws_exception_when_date_is_after_end_date(): void
    {
        $model = SemesterModel::factory()->create();

        self::expectException(SemesterNotFoundException::class);

        $this->repository()->getByDate(CarbonImmutable::parse($model->end_date)->addDay());
    }

    public function test_get_latest_semester(): void
    {
        $this->repository()->save($this->createSemester(
            academicYear: new AcademicYear('2025'),
            term: Term::THIRD,
            startDate: new DateTimeImmutable('2025-10-01'),
            endDate: new DateTimeImmutable('2025-12-31'),
        ));

        $this->repository()->save($this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::FIRST,
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-03-31'),
        ));

        $expected = $this->createSemester(
            academicYear: new AcademicYear('2026'),
            term: Term::SECOND,
            startDate: new DateTimeImmutable('2026-04-01'),
            endDate: new DateTimeImmutable('2026-06-30'),
        );

        $this->repository()->save($expected);

        $actual = $this->repository()->getLatest();

        self::assertInstanceOf(Semester::class, $actual);
        self::assertSame($expected->academicYear()->value(), $actual->academicYear()->value());
        self::assertSame($expected->term()->value, $actual->term()->value);
        self::assertSame($expected->startDate()->format('Y-m-d'), $actual->startDate()->format('Y-m-d'));
        self::assertSame($expected->endDate()->format('Y-m-d'), $actual->endDate()->format('Y-m-d'));
    }

    public function test_get_latest_throws_exception_when_no_semesters_exist(): void
    {
        self::expectException(SemesterNotFoundException::class);

        $this->repository()->getLatest();
    }
}
