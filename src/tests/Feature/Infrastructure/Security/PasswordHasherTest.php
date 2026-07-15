<?php

namespace Tests\Feature\Infrastructure\Security;

use App\Application\Security\PasswordHasherInterface;
use RuntimeException;
use Tests\TestCase;

final class PasswordHasherTest extends TestCase
{
    private PasswordHasherInterface $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasher = $this->app->make(PasswordHasherInterface::class);
    }

    public function test_hashes_password(): void
    {
        $hashed = $this->hasher->hash('pass1234');

        $this->assertNotEmpty($hashed);
        $this->assertNotSame('pass1234', $hashed);
    }

    public function test_returns_true_when_password_matches_hash(): void
    {
        $hashed = $this->hasher->hash('pass1234');

        $this->assertTrue($this->hasher->verify('pass1234', $hashed));
    }

    public function test_returns_false_when_password_is_invalid(): void
    {
        $hashed = $this->hasher->hash('pass1234');

        $this->assertFalse($this->hasher->verify('invalid', $hashed));
    }

    public function test_throws_exception_when_hash_is_invalid(): void
    {
        $this->expectException(RuntimeException::class);

        $this->hasher->verify('pass1234', 'invalid-hash');
    }
}
