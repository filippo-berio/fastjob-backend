<?php

namespace App\Auth\Service\Token;

use App\Auth\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class CreateAccessTokenService
{
    public function __construct(
        private RedisTokenService   $redisTokenService,
        private JWTEncoderInterface $JWTEncoder,
    ) {
    }

    public function create(User $user): string
    {
        $token = $this->JWTEncoder->encode([
            'userId' => $user->getId(),
        ]);
        $this->redisTokenService->setAccessToken($token, $user);
        return $token;
    }
}
