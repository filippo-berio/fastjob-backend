<?php

namespace App\Tests\Unit\Core\UseCase\Auth;

use App\Authistic\Adapter\AuthisticAdapter;
use App\Authistic\DTO\TokenPair;
use App\Core\UseCase\Auth\LoginUseCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LoginUseCaseTest extends KernelTestCase
{
    public function testSuccess()
    {
        $authisticAdapter = $this->createMock(AuthisticAdapter::class);
        $authisticAdapter
            ->expects($this->once())
            ->method('login')
            ->with('user', 'password')
            ->willReturn(new TokenPair('access-token', 'refresh-token'));

        $useCase = new LoginUseCase($authisticAdapter);
        $tokens = $useCase('user', 'password');
        $this->assertEquals('access-token', $tokens->getAccessToken());
        $this->assertEquals('refresh-token', $tokens->getRefreshToken());
    }
}
