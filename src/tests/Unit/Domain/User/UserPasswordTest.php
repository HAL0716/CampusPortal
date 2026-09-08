<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\Exceptions\InvalidUserPasswordException;
use App\Domain\User\ValueObjects\UserPassword;
use PHPUnit\Framework\TestCase;

final class UserPasswordTest extends TestCase
{
    public function test_creates_valid_password(): void
    {
        $password = UserPassword::create('pass1234');

        $this->assertSame('pass1234', $password->value());
        $this->assertFalse($password->isHashed());
    }

    public function test_throws_exception_when_password_is_invalid(): void
    {
        $this->expectException(InvalidUserPasswordException::class);

        UserPassword::create('short');
    }

    public function test_creates_hashed_password(): void
    {
        $password = UserPassword::fromHash('$2y$04$KPSmno5kdzCzeERbPvLGW.oehD.NdNf7Dlr2J65lYium3zHWvDZBO');

        $this->assertSame('$2y$04$KPSmno5kdzCzeERbPvLGW.oehD.NdNf7Dlr2J65lYium3zHWvDZBO', $password->value());
        $this->assertTrue($password->isHashed());
    }

    public function test_throws_exception_when_hashed_password_is_invalid(): void
    {
        $this->expectException(InvalidUserPasswordException::class);

        UserPassword::fromHash('invalid-hash');
    }
}
