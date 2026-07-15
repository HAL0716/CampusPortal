<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\Exceptions\InvalidUserEmailException;
use PHPUnit\Framework\TestCase;
use Tests\Support\User\CreatesDomainUser;

final class UserEmailTest extends TestCase
{
    use CreatesDomainUser;

    public function test_creates_valid_email(): void
    {
        $email = $this->userEmailValueObject();

        $this->assertSame($this->userEmail(), $email->value());
    }

    public function test_throws_exception_when_email_is_invalid(): void
    {
        $this->expectException(InvalidUserEmailException::class);

        $this->userEmailValueObject($this->invalidUserEmail());
    }
}
