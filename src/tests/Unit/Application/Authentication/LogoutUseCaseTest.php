<?php

namespace Tests\Unit\Application\Authentication;

use App\Application\Authentication\AuthenticationServiceInterface;
use App\Application\Authentication\LogoutUseCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

final class LogoutUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_logout(): void
    {
        $auth = Mockery::mock(AuthenticationServiceInterface::class);

        $auth->shouldReceive('logout')
            ->once();

        $this->useCase($auth)->execute();
    }

    private function useCase(AuthenticationServiceInterface $auth): LogoutUseCase
    {
        return new LogoutUseCase($auth);
    }
}
