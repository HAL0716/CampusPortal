<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Teacher\Entities\Teacher;
use App\Infrastructure\Repositories\EloquentTeacherRepository;
use App\Models\Teacher as TeacherModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers\TeacherTestHelper;
use Tests\TestCase;

final class EloquentTeacherRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use TeacherTestHelper;

    private function repository(): EloquentTeacherRepository
    {
        return app(EloquentTeacherRepository::class);
    }

    public function test_find_by_user_id_returns_teacher(): void
    {
        $model = TeacherModel::factory()->create();

        $result = $this->repository()->findByUserId($this->userId($model->user_id));

        self::assertInstanceOf(Teacher::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
        self::assertSame($model->user_id, $result->userId()->value());
    }

    public function test_find_by_user_id_returns_null_when_teacher_not_found(): void
    {
        self::assertNull($this->repository()->findByUserId($this->userId(999999)));
    }
}
