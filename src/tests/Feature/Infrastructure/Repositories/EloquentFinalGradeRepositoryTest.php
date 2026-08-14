<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Enrollment\EnrollmentId;
use App\Domain\FinalGrade\Entities\FinalGrade;
use App\Domain\FinalGrade\Enums\FinalGradeType;
use App\Domain\FinalGrade\ValueObjects\FinalGradeId;
use App\Infrastructure\Repositories\EloquentFinalGradeRepository;
use App\Models\Enrollment;
use App\Models\FinalGrade as FinalGradeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentFinalGradeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentFinalGradeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EloquentFinalGradeRepository::class);
    }

    public function test_can_save_new_final_grade(): void
    {
        $enrollment = Enrollment::factory()->create();

        $saved = $this->repository->save(
            FinalGrade::create(
                new EnrollmentId($enrollment->id),
                FinalGradeType::A,
            )
        );

        self::assertInstanceOf(FinalGrade::class, $saved);
        self::assertNotNull($saved->id());
        self::assertDatabaseHas('final_grades', [
            'enrollment_id' => $enrollment->id,
            'grade' => FinalGradeType::A->value,
        ]);
    }

    public function test_can_update_existing_final_grade(): void
    {
        $finalGrade = FinalGradeModel::factory()->create();

        $saved = $this->repository->save(
            FinalGrade::reconstruct(
                new FinalGradeId($finalGrade->id),
                new EnrollmentId($finalGrade->enrollment_id),
                FinalGradeType::B,
            )
        );

        self::assertInstanceOf(FinalGrade::class, $saved);
        self::assertSame($finalGrade->id, $saved->id()->value());
        self::assertDatabaseHas('final_grades', [
            'enrollment_id' => $finalGrade->enrollment_id,
            'grade' => FinalGradeType::B->value,
        ]);
    }
}
