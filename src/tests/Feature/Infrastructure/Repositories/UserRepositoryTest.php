<?php

namespace Tests\Feature\Infrastructure\Repositories;

use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\UserRepositoryInterface;
use App\Models\User as UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Support\User\CreatesDomainUser;
use Tests\TestCase;

final class UserRepositoryTest extends TestCase
{
    use CreatesDomainUser;
    use RefreshDatabase;

    private UserRepositoryInterface $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = $this->app->make(UserRepositoryInterface::class);
    }

    public function test_saves_user(): void
    {
        $user = $this->users->save($this->createUser());

        $this->assertNotNull($user->id());

        $this->assertDatabaseHas('users', [
            'id' => $user->id()->value(),
            'name' => $user->name(),
        ]);
    }

    public function test_hashes_plain_password_when_saving_user(): void
    {
        $user = $this->users->save($this->createUser());

        $model = UserModel::find($user->id()->value());

        $this->assertNotSame($this->userPassword(), $model->password);
        $this->assertTrue(Hash::check($this->userPassword(), $model->password));
    }

    public function test_preserves_hashed_password_when_saving_user(): void
    {
        $user = $this->users->save(
            $this->createUser(
                hashed: true
            )
        );

        $model = UserModel::find($user->id()->value());

        $this->assertSame($this->hashedUserPassword(), $model->password);
    }

    public function test_updates_existing_user(): void
    {
        $default = $this->users->save($this->createUser());

        $updated = $this->users->save(
            $this->reconstructUser(
                id: $default->id()->value(),
                name: 'Updated Name',
            )
        );

        $this->assertSame('Updated Name', $updated->name());

        $this->assertDatabaseHas('users', [
            'id' => $default->id()->value(),
            'name' => 'Updated Name',
        ]);
    }

    public function test_throws_exception_when_updating_non_existing_user(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->users->save(
            $this->reconstructUser(
                id: PHP_INT_MAX
            )
        );
    }

    public function test_throws_exception_when_saving_user_with_duplicate_email(): void
    {
        $this->users->save($this->createUser());

        $this->expectException(UserAlreadyExistsException::class);

        $this->users->save($this->createUser());
    }

    public function test_finds_user_by_id(): void
    {
        $expected = $this->users->save($this->createUser());

        $actual = $this->users->findById($expected->id());

        $this->assertNotNull($actual);
        $this->assertSame($expected->id()->value(), $actual->id()->value());
        $this->assertSame($expected->email()->value(), $actual->email()->value());
        $this->assertSame($expected->name(), $actual->name());
    }

    public function test_finds_user_by_email(): void
    {
        $expected = $this->users->save($this->createUser());

        $actual = $this->users->findByEmail(
            $this->userEmailValueObject()
        );

        $this->assertNotNull($actual);
        $this->assertSame($expected->email()->value(), $actual->email()->value());
        $this->assertSame($expected->name(), $actual->name());
    }

    public function test_returns_null_when_user_is_not_found_by_id(): void
    {
        $this->assertNull(
            $this->users->findById(
                $this->userIdValueObject()
            )
        );
    }

    public function test_returns_null_when_user_is_not_found_by_email(): void
    {
        $this->assertNull(
            $this->users->findByEmail(
                $this->userEmailValueObject()
            )
        );
    }
}
