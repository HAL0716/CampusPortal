<?php

namespace Tests\Feature\Infrastructure\QueryServices;

use App\Application\Contexts\Semester\DTOs\SemesterDTO;
use App\Domain\Academic\Enums\Term;
use App\Infrastructure\QueryServices\EloquentSemesterQueryService;
use App\Models\Semester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentSemesterQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    private EloquentSemesterQueryService $queryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryService = new EloquentSemesterQueryService;
    }

    public function test_find_all_returns_semesters_in_order(): void
    {
        $semesters = [
            [
                'id' => 1,
                'academic_year' => '2026',
                'term' => Term::SECOND,
                'start_date' => '2026-04-01',
                'end_date' => '2026-06-30',
            ],
            [
                'id' => 2,
                'academic_year' => '2026',
                'term' => Term::FIRST,
                'start_date' => '2026-01-01',
                'end_date' => '2026-03-31',
            ],
        ];

        Semester::factory()->createMany($semesters);

        $expected = collect($semesters)
            ->sortByDesc('end_date')
            ->map(
                fn (array $semester) => new SemesterDTO(
                    id: (string) $semester['id'],
                    academicYear: $semester['academic_year'],
                    term: $semester['term']->value,
                    startDate: $semester['start_date'],
                    endDate: $semester['end_date'],
                )
            )
            ->all();

        $result = $this->queryService->findAll();

        self::assertEquals($expected, $result);
    }
}
