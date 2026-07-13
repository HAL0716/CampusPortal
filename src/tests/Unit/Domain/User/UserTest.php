<?php

namespace Tests\Unit\Domain\User;

use PHPUnit\Framework\TestCase;
use Tests\Support\User\CreatesDomainUser;

final class UserTest extends TestCase
{
    use CreatesDomainUser;

    public function test_creates_valid_user(): void
    {
        $user = $this->createUser();

        $this->assertNull($user->id());
        $this->assertSame($this->userEmail(), $user->email()->value());
        $this->assertSame($this->userPassword(), $user->password()->value());
        $this->assertSame($this->userName(), $user->name());
    }

    public function test_reconstructs_valid_user(): void
    {
        $user = $this->reconstructUser();

        $this->assertSame($this->userId(), $user->id()->value());
        $this->assertSame($this->userEmail(), $user->email()->value());
        $this->assertSame($this->hashedUserPassword(), $user->password()->value());
        $this->assertSame($this->userName(), $user->name());
    }
}
