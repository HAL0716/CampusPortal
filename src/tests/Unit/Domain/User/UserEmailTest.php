<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\Exceptions\InvalidUserEmailException;
use App\Domain\User\ValueObjects\UserEmail;
use PHPUnit\Framework\TestCase;

final class UserEmailTest extends TestCase
{
    public function test_creates_valid_email(): void
    {
        $email = new UserEmail('test@example.com');

        $this->assertSame('test@example.com', $email->value());
    }

    public function test_throws_exception_when_email_is_invalid(): void
    {
        $this->expectException(InvalidUserEmailException::class);

        new UserEmail('invalid-email');
    }
}
