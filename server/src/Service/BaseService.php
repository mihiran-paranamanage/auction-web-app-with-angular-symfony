<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Utility\UserRoleManager;

/**
 * Class BaseService
 * @package App\Service
 */
class BaseService
{
    const DATA_GROUP_ADMIN_DASHBOARD = 'admin_dashboard';
    const DATA_GROUP_BID = 'bid';
    const DATA_GROUP_BID_HISTORY = 'bid_history';
    const DATA_GROUP_CONFIGURE_AUTO_BID = 'configure_auto_bid';
    const DATA_GROUP_ITEM = 'item';

    const PERMISSION_TYPE_CAN_READ = 'canRead';
    const PERMISSION_TYPE_CAN_CREATE = 'canCreate';
    const PERMISSION_TYPE_CAN_UPDATE = 'canUpdate';
    const PERMISSION_TYPE_CAN_DELETE = 'canDelete';

    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $userRoleManager;
    private $user;

    /**
     * @return UserRoleManager
     */
    public function getUserRoleManager() : UserRoleManager {
        if (!($this->userRoleManager instanceof UserRoleManager)) {
            $this->userRoleManager = new UserRoleManager($this->accessTokenRepository, $this->userRoleDataGroupRepository);
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
     * BaseService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
    }
}
