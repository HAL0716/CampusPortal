<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Semester\Entities\Semester;
use App\Infrastructure\Repositories\EloquentSemesterRepository;
use App\Models\Semester as SemesterModel;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentSemesterRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentSemesterRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EloquentSemesterRepository::class);
    }

    public function test_can_find_semester_by_date(): void
    {
        $semester = SemesterModel::factory()->create([
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
        ]);

        $date = new CarbonImmutable('2024-03-15');

        $found = $this->repository->findByDate($date);

        self::assertInstanceOf(Semester::class, $found);
        self::assertSame($semester->id, $found->id()->value());
    }

    public function test_returns_null_when_no_semester_found_by_date(): void
    {
        $date = new CarbonImmutable('2024-03-15');

        $found = $this->repository->findByDate($date);

        self::assertNull($found);
    }
}
