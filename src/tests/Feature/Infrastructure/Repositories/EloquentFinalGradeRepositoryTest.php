<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Domain\FinalGrade\Exceptions\FinalGradeAlreadyExistsException;
use App\Domain\FinalGrade\Exceptions\FinalGradeNotFoundException;
use App\Infrastructure\Repositories\EloquentFinalGradeRepository;
use App\Models\Enrollment;
use App\Models\FinalGrade as FinalGradeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\FinalGradeTestHelper;
use Tests\TestCase;

final class EloquentFinalGradeRepositoryTest extends TestCase
{
    use FinalGradeTestHelper;
    use RefreshDatabase;

    private function repository(): EloquentFinalGradeRepository
    {
        return app(EloquentFinalGradeRepository::class);
    }

    public function test_save_creates_final_grade(): void
    {
        $finalGrade = $this->createFinalGrade(
            enrollmentId: Enrollment::factory()->create()->id,
        );

        $result = $this->repository()->save($finalGrade);

        self::assertNotNull($result->id());
        self::assertSame($finalGrade->enrollmentId()->value(), $result->enrollmentId()->value());
        self::assertSame($finalGrade->grade(), $result->grade());

        $this->assertDatabaseHas('final_grades', [
            'id' => $result->requireId()->value(),
            'enrollment_id' => $finalGrade->enrollmentId()->value(),
            'grade' => $finalGrade->grade(),
        ]);
    }

    public function test_save_updates_existing_final_grade(): void
    {
        $model = FinalGradeModel::factory()->withGrade(FinalGradeType::A)->create();

        $finalGrade = $this->reconstructFinalGrade(
            id: $model->id,
            enrollmentId: $model->enrollment_id,
            grade: FinalGradeType::B,
        );

        $result = $this->repository()->save($finalGrade);

        self::assertSame($finalGrade->requireId()->value(), $result->requireId()->value());
        self::assertSame($finalGrade->enrollmentId()->value(), $result->enrollmentId()->value());
        self::assertSame($finalGrade->grade(), $result->grade());

        $this->assertDatabaseHas('final_grades', [
            'id' => $finalGrade->requireId()->value(),
            'enrollment_id' => $finalGrade->enrollmentId()->value(),
            'grade' => $finalGrade->grade(),
        ]);
    }

    public function test_save_throws_exception_when_updating_nonexistent_final_grade(): void
    {
        $finalGrade = $this->reconstructFinalGrade(
            id: 999999,
            enrollmentId: Enrollment::factory()->create()->id,
        );

        $this->expectException(FinalGradeNotFoundException::class);

        $this->repository()->save($finalGrade);
    }

    public function test_save_throws_exception_when_final_grade_already_exists(): void
    {
        $enrollment = Enrollment::factory()->create();

        FinalGradeModel::factory()->for($enrollment)->create();

        $finalGrade = $this->createFinalGrade(
            enrollmentId: $enrollment->id
        );

        $this->expectException(FinalGradeAlreadyExistsException::class);

        $this->repository()->save($finalGrade);
    }
}
