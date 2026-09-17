<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Academic\Enums\Term;
use App\Infrastructure\Repositories\EloquentCourseRepository;
use App\Models\Course as CourseModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\CourseTestHelper;
use Tests\TestCase;

final class EloquentCourseRepositoryTest extends TestCase
{
    use CourseTestHelper;
    use RefreshDatabase;

    private function repository(): EloquentCourseRepository
    {
        return app(EloquentCourseRepository::class);
    }

    public function test_get_by_term(): void
    {
        CourseModel::factory()->createMany(
            [
                ['id' => 1, 'term' => Term::FIRST],
                ['id' => 2, 'term' => Term::SECOND],
            ]
        );

        $result = $this->repository()->getByTerm(Term::FIRST);

        self::assertCount(1, $result);
        self::assertSame(1, $result[0]->id()->value());
    }
}
