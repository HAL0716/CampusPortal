<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Student\Entities\Student;
use App\Domain\Student\Exceptions\StudentNotFoundException;
use App\Infrastructure\Repositories\EloquentStudentRepository;
use App\Models\Student as StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\StudentTestHelper;
use Tests\TestCase;

final class EloquentStudentRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use StudentTestHelper;

    private function repository(): EloquentStudentRepository
    {
        return app(EloquentStudentRepository::class);
    }

    public function test_find_returns_student(): void
    {
        $model = StudentModel::factory()->create();

        $result = $this->repository()->find($this->studentId($model->id));

        self::assertInstanceOf(Student::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_find_returns_null_when_student_not_found(): void
    {
        self::assertNull($this->repository()->find($this->studentId(999999)));
    }

    public function test_get_returns_student(): void
    {
        $model = StudentModel::factory()->create();

        $result = $this->repository()->get($this->studentId($model->id));

        self::assertInstanceOf(Student::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_get_throws_exception_when_student_not_found(): void
    {
        $this->expectException(StudentNotFoundException::class);

        $this->repository()->get($this->studentId(999999));
    }

    public function test_find_by_user_id_returns_student(): void
    {
        $model = StudentModel::factory()->create();

        $result = $this->repository()->findByUserId($this->userId($model->user_id));

        self::assertInstanceOf(Student::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
        self::assertSame($model->user_id, $result->userId()->value());
    }

    public function test_find_by_user_id_returns_null_when_student_not_found(): void
    {
        self::assertNull($this->repository()->findByUserId($this->userId(999999)));
    }

    public function test_get_by_user_id_returns_student(): void
    {
        $model = StudentModel::factory()->create();

        $result = $this->repository()->getByUserId($this->userId($model->user_id));

        self::assertInstanceOf(Student::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
        self::assertSame($model->user_id, $result->userId()->value());
    }

    public function test_get_by_user_id_throws_exception_when_student_not_found(): void
    {
        $this->expectException(StudentNotFoundException::class);

        $this->repository()->getByUserId($this->userId(999999));
    }
}
