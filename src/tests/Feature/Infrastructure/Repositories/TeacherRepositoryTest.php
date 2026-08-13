<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\Teacher\Entities\Teacher;
use App\Domain\User\ValueObjects\UserId;
use App\Infrastructure\Repositories\TeacherRepository;
use App\Models\Teacher as TeacherModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TeacherRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TeacherRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(TeacherRepository::class);
    }

    public function test_can_find_teacher_by_user_id(): void
    {
        $teacher = TeacherModel::factory()->create();

        $found = $this->repository->findByUserId(new UserId($teacher->user_id));

        self::assertInstanceOf(Teacher::class, $found);
        self::assertSame($teacher->id, $found->id()->value());
        self::assertSame($teacher->user_id, $found->userId()->value());
    }

    public function test_returns_null_when_teacher_not_found_by_user_id(): void
    {
        $found = $this->repository->findByUserId(new UserId(999));

        self::assertNull($found);
    }
}
