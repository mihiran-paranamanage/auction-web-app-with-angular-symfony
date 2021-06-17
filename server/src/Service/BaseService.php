<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Utility\UserRoleManager;

/**
 * Class BaseService
 * @package App\Service
 */
class BaseService
{
    private $accessTokenRepository;
    private $userRoleManager;
    private $user;

    /**
     * @return UserRoleManager
     */
    public function getUserRoleManager() : UserRoleManager {
        if (!($this->userRoleManager instanceof UserRoleManager)) {
            $this->userRoleManager = new UserRoleManager($this->accessTokenRepository);
        }
        return $this->userRoleManager;
    }

    /**
     * @param UserRoleManager $userRoleManager
     */
    public function setUserRoleManager(UserRoleManager $userRoleManager) {
        $this->userRoleManager = $userRoleManager;
    }

    /**
     * @param string $accessToken
     * @return User|null
     */
    public function getUser(string $accessToken) : ?User {
        if (!($this->user instanceof User)) {
            $this->user = $this->getUserRoleManager()->getUserByAccessToken($accessToken);
        }
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user = $user;
    }

    /**
     * AutoBidConfigController constructor.
     * @param AccessTokenRepository $accessTokenRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
    }
}