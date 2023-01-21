<?php

namespace App\Auth\Service\Token;

use App\Auth\Entity\AccessToken;
use App\Auth\Entity\User;
use App\Auth\Repository\AccessTokenRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class CreateAccessTokenService
{
    public function __construct(
        private JWTEncoderInterface $JWTEncoder,
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function create(User $user): string
    {
        $token = $this->JWTEncoder->encode([
            'userId' => $user->getId(),
        ]);
        $this->accessTokenRepository->save(new AccessToken($user, $token));
        return $token;
    }
}
