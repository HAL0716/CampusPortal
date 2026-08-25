<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\Exceptions\UserIdNotAssignedException;
use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\UserTestHelper;

final class UserTest extends TestCase
{
    use UserTestHelper;

    public function test_creates_user_without_id(): void
    {
        $user = $this->createUser();

        $this->assertNull($user->id());
        $this->assertSame($this->userEmail()->value(), $user->email()->value());
        $this->assertSame($this->userPassword()->value(), $user->password()->value());
        $this->assertSame($this->userName(), $user->name());
    }

    public function test_reconstructs_user_with_id(): void
    {
        $user = $this->reconstructUser();

        $this->assertSame($this->userId()->value(), $user->id()->value());
        $this->assertSame($this->userEmail()->value(), $user->email()->value());
        $this->assertSame($this->hashedUserPassword()->value(), $user->password()->value());
        $this->assertSame($this->userName(), $user->name());
    }

    public function test_returns_assigned_id(): void
    {
        $user = $this->reconstructUser();

        $this->assertSame($user->id()->value(), $user->requireId()->value());
    }

    public function test_throws_exception_when_id_is_not_assigned(): void
    {
        $this->expectException(UserIdNotAssignedException::class);

        $this->createUser()->requireId();
    }
}
