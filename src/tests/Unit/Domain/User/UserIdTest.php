<?php

namespace Tests\Unit\Domain\User;

use PHPUnit\Framework\TestCase;
use Tests\Support\User\CreatesDomainUser;

final class UserIdTest extends TestCase
{
    use CreatesDomainUser;

    public function test_creates_valid_user_id(): void
    {
        $id = $this->userIdValueObject();

        $this->assertSame($this->userId(), $id->value());
    }
}
