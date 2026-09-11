<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Infrastructure\Repositories\EloquentUserRepository;
use App\Models\User as UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Support\TestHelpers\UserTestHelper;
use Tests\TestCase;

final class EloquentUserRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use UserTestHelper;

    private function repository(): EloquentUserRepository
    {
        return app(EloquentUserRepository::class);
    }

    public function test_save_creates_user_with_hashed_password(): void
    {
        $user = $this->createUser();

        $result = $this->repository()->save($user);

        self::assertInstanceOf(User::class, $result);
        self::assertNotNull($result->id());
        self::assertSame($user->email()->value(), $result->email()->value());
        self::assertSame($user->name(), $result->name());
        self::assertTrue(Hash::check($user->password()->value(), $result->password()->value()));

        $this->assertDatabaseHas('users', [
            'id' => $result->requireId()->value(),
            'email' => $result->email()->value(),
            'password' => $result->password()->value(),
            'name' => $result->name(),
            'status' => $result->status()->value,
        ]);
    }

    public function test_save_does_not_rehash_hashed_password(): void
    {
        $model = UserModel::factory()->create();

        $user = $this->reconstructUser($model->id, $model->email, $model->password, $model->name);

        $result = $this->repository()->save($user);

        self::assertSame($user->requireId()->value(), $result->requireId()->value());
        self::assertSame($user->password()->value(), $result->password()->value());
    }

    public function test_save_updates_existing_user(): void
    {
        $model = UserModel::factory()->create(['name' => '更新前']);

        $user = $this->reconstructUser($model->id, $model->email, $model->password, '更新後');

        $result = $this->repository()->save($user);

        self::assertSame($user->requireId()->value(), $result->requireId()->value());
        self::assertSame($user->name(), $result->name());
    }

    public function test_save_throws_exception_when_updating_nonexistent_user(): void
    {
        $user = $this->reconstructUser(id: 999999);

        $this->expectException(UserNotFoundException::class);

        $this->repository()->save($user);
    }

    public function test_save_throws_exception_when_user_already_exists(): void
    {
        $model = UserModel::factory()->create();

        $user = $this->createUser(email: $model->email);

        $this->expectException(UserAlreadyExistsException::class);

        $this->repository()->save($user);
    }

    public function test_find_by_id_returns_user(): void
    {
        $model = UserModel::factory()->create();

        $result = $this->repository()->findById($this->userId($model->id));

        self::assertInstanceOf(User::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
    }

    public function test_find_by_id_returns_null_when_user_not_found(): void
    {
        self::assertNull($this->repository()->findById($this->userId(999999)));
    }

    public function test_find_by_email_returns_user(): void
    {
        $model = UserModel::factory()->create();

        $result = $this->repository()->findByEmail($this->userEmail($model->email));

        self::assertInstanceOf(User::class, $result);
        self::assertSame($model->id, $result->requireId()->value());
        self::assertSame($model->email, $result->email()->value());
    }

    public function test_find_by_email_returns_null_when_user_not_found(): void
    {
        self::assertNull($this->repository()->findByEmail($this->userEmail('not-found@example.com')));
    }
}
