<?php

namespace App\Utility;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;

/**
 * Class UserRoleManager
 * @package App\Utility
 */
class UserRoleManager
{
    private $accessTokenRepository;

    /**
     * UserRoleManager constructor.
     * @param AccessTokenRepository $accessTokenRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function getUserByAccessToken(string $token) : ?User {
        $accessToken = $this->accessTokenRepository->findOneBy(array('token' => $token));
        if ($accessToken instanceof AccessToken) {
            return $accessToken->getUser();
        } else {
            return null;
        }
    }
}