<?php

namespace Tests\Unit\Domain\User;

use PHPUnit\Framework\TestCase;
use Tests\Support\TestHelpers\UserTestHelper;

final class UserTest extends TestCase
{
    use UserTestHelper;

    public function test_creates_valid_user(): void
    {
        $user = $this->createUser();

        $this->assertNull($user->id());
        $this->assertSame($this->userEmail()->value(), $user->email()->value());
        $this->assertSame($this->userPassword()->value(), $user->password()->value());
        $this->assertSame($this->userName(), $user->name());
    }

    public function test_reconstructs_valid_user(): void
    {
        $user = $this->reconstructUser();

        $this->assertSame($this->userId()->value(), $user->id()->value());
        $this->assertSame($this->userEmail()->value(), $user->email()->value());
        $this->assertSame($this->hashedUserPassword()->value(), $user->password()->value());
        $this->assertSame($this->userName(), $user->name());
    }
}
