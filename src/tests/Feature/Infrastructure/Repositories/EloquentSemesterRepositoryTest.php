<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Semester\Entities\Semester;
use App\Domain\Semester\Exceptions\SemesterNotFoundException;
use App\Infrastructure\Repositories\EloquentSemesterRepository;
use App\Models\Semester as SemesterModel;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentSemesterRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private function repository(): EloquentSemesterRepository
    {
        return app(EloquentSemesterRepository::class);
    }

    public function test_get_by_date_returns_semester_when_date_is_start_date(): void
    {
        $model = SemesterModel::factory()->create();

        $result = $this->repository()->getByDate(CarbonImmutable::parse($model->start_date));

        self::assertInstanceOf(Semester::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
        self::assertSame($model->academic_year, $result->academicYear());
        self::assertSame($model->term->value, $result->term()->value);
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
}
