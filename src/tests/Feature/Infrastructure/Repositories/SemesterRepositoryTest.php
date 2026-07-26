<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Semester\Semester;
use App\Infrastructure\Repositories\SemesterRepository;
use App\Models\Semester as SemesterModel;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SemesterRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SemesterRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(SemesterRepository::class);
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
