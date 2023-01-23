<?php

namespace App\Tests\Functional\Auth\Service;

use App\Auth\Entity\AccessToken;
use App\Auth\Entity\User;
use App\Auth\Repository\AccessTokenRepository;
use App\Auth\Service\AuthenticateService;
use App\DataFixtures\Auth\RefreshTokenFixtures;
use App\DataFixtures\Auth\UserFixtures;
use App\Tests\Functional\FunctionalTest;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticateServiceTest extends FunctionalTest
{
    public function testActualTokens()
    {
        $this->bootContainer();
        $accessToken = $this->setAccessTokenInRedis(UserFixtures::USER_1);
        $service = $this->getDependency(AuthenticateService::class);
        $user = $service->authenticate($accessToken, RefreshTokenFixtures::REFRESH_TOKEN_1);
        $this->assertEquals($accessToken, $user->getAccessToken());
        $this->assertEquals(RefreshTokenFixtures::REFRESH_TOKEN_1, $user->getRefreshToken()->getToken());
        $this->assertEquals(UserFixtures::USER_1, $user->getId());
    }

    public function testRottenAccessActualRefreshToken()
    {
        $this->bootContainer();
        $accessToken = $this->setAccessTokenInRedis(UserFixtures::USER_2);
        sleep(3);
        $service = $this->getDependency(AuthenticateService::class);
        $user = $service->authenticate($accessToken, RefreshTokenFixtures::REFRESH_TOKEN_2);
        $this->assertNotEquals($accessToken, $user->getAccessToken());
        $this->assertNotEquals(RefreshTokenFixtures::REFRESH_TOKEN_2, $user->getRefreshToken()->getToken());
        $this->assertEquals(UserFixtures::USER_2, $user->getId());
    }

    public function testActualAccessInvalidRefreshToken()
    {
        $this->bootContainer();
        $accessToken = $this->setAccessTokenInRedis(UserFixtures::USER_1);
        $service = $this->getDependency(AuthenticateService::class);
        $user = $service->authenticate($accessToken);
        $this->assertEquals($accessToken, $user->getAccessToken());
        $this->assertEquals(RefreshTokenFixtures::REFRESH_TOKEN_1, $user->getRefreshToken()->getToken());
        $this->assertEquals(UserFixtures::USER_1, $user->getId());
    }

    public function testRottenAccessOtherRefreshToken()
    {
        $this->bootContainer();
        $accessToken = $this->setAccessTokenInRedis(UserFixtures::USER_2);
        sleep(3);
        $service = $this->getDependency(AuthenticateService::class);
        $this->expectException(AuthenticationException::class);
        $service->authenticate($accessToken, RefreshTokenFixtures::REFRESH_TOKEN_3);
    }

    public function testRottenActualInvalidRefreshToken()
    {
        $this->bootContainer();
        $accessToken = $this->setAccessTokenInRedis(UserFixtures::USER_2);
        sleep(3);
        $service = $this->getDependency(AuthenticateService::class);
        $this->expectException(AuthenticationException::class);
        $service->authenticate($accessToken, 'invalid-refresh-token');
    }

    public function testOtherAccessActualRefreshToken()
    {
        $this->bootContainer();
        $accessToken = $this->setAccessTokenInRedis(UserFixtures::USER_2);
        $this->setAccessTokenInRedis(UserFixtures::USER_3);
        sleep(3);
        $service = $this->getDependency(AuthenticateService::class);
        $this->expectException(AuthenticationException::class);
        $service->authenticate($accessToken, RefreshTokenFixtures::REFRESH_TOKEN_3);
    }

    public function testUserWithoutProfileAndRefreshToken()
    {
        $this->bootContainer();
        $accessToken = $this->setAccessTokenInRedis(UserFixtures::USER_6);
        $service = $this->getDependency(AuthenticateService::class);
        $user = $service->authenticate($accessToken);
        $this->assertEquals($accessToken, $user->getAccessToken());
        $this->assertEquals(UserFixtures::USER_6, $user->getId());
    }

    private function setAccessTokenInRedis(int $userId): string
    {
        $user = $this->getEntity(User::class, $userId);
        $token = $this->createAccessToken($userId);
        $repo = $this->getDependency(AccessTokenRepository::class);
        $repo->save(new AccessToken($user, $token));
        return $token;
    }

    private function createAccessToken(int $userId): string
    {
        $JWTEncoder = $this->getDependency(JWTEncoderInterface::class);
        return $JWTEncoder->encode([
            'userId' => $userId
        ]);
    }
}
