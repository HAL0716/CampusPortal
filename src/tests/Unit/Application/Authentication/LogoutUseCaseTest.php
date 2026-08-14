<?php

namespace Tests\Unit\Application\Authentication;

use App\Application\Contexts\Authentication\AuthenticationService;
use App\Application\Contexts\Authentication\UseCases\LogoutUseCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

final class LogoutUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_logout(): void
    {
        $auth = Mockery::mock(AuthenticationService::class);

        $auth->shouldReceive('logout')
            ->once();

        $this->useCase($auth)->execute();
    }

    private function useCase(AuthenticationService $auth): LogoutUseCase
    {
        return new LogoutUseCase($auth);
    }
}
