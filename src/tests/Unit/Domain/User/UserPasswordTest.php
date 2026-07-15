<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\Exceptions\InvalidUserPasswordException;
use PHPUnit\Framework\TestCase;
use Tests\Support\User\CreatesDomainUser;

final class UserPasswordTest extends TestCase
{
    use CreatesDomainUser;

    public function test_creates_valid_password(): void
    {
        $password = $this->userPasswordValueObject();

        $this->assertSame($this->userPassword(), $password->value());
        $this->assertFalse($password->isHashed());
    }

    public function test_throws_exception_when_password_is_invalid(): void
    {
        $this->expectException(InvalidUserPasswordException::class);

        $this->userPasswordValueObject($this->invalidUserPassword());
    }

    public function test_creates_hashed_password(): void
    {
        $password = $this->hashedUserPasswordValueObject();

        $this->assertSame($this->hashedUserPassword(), $password->value());
        $this->assertTrue($password->isHashed());
    }

    public function test_throws_exception_when_hashed_password_is_invalid(): void
    {
        $this->expectException(InvalidUserPasswordException::class);

        $this->hashedUserPasswordValueObject($this->userPassword());
    }
}
