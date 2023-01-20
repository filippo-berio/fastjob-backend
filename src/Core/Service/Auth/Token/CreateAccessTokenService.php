<?php

namespace App\Core\Service\Auth\Token;

use App\Core\Entity\User;
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
