<?php

namespace Tests\Unit\Infrastructure\Security;

use App\Infrastructure\Security\LaravelPasswordHasher;
use Tests\TestCase;

final class LaravelPasswordHasherTest extends TestCase
{
    private LaravelPasswordHasher $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasher = new LaravelPasswordHasher;
    }

    public function test_can_hash_password(): void
    {
        $password = 'pass1234';

        $hashed = $this->hasher->hash($password);

        $this->assertNotSame($password, $hashed);
        $this->assertTrue($this->hasher->verify($password, $hashed));
    }

    public function test_cannot_verify_invalid_password(): void
    {
        $hashed = $this->hasher->hash('pass1234');

        $this->assertFalse($this->hasher->verify('invalid', $hashed));
    }
}
