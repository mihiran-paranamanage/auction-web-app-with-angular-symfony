<?php

namespace App\Utility;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\UserRoleDataGroupRepository;

/**
 * Class UserRoleManager
 * @package App\Utility
 */
class UserRoleManager
{
    private $accessTokenRepository;
    private $userRoleDataGroupRepository;

    /**
     * UserRoleManager constructor.
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

    /**
     * @param string $accessToken
     * @return array
     */
    public function getPermissions(string $accessToken) : array {
        $permissions = array();
        $user = $this->getUserByAccessToken($accessToken);
        if ($user instanceof User) {
            $userRoleDataGroups = $this->userRoleDataGroupRepository->findBy(array('userRole' => $user->getUserRole()));
            foreach ($userRoleDataGroups as $userRoleDataGroup) {
                $permission = array();
                $permission['canRead'] = $userRoleDataGroup->getCanRead();
                $permission['canCreate'] = $userRoleDataGroup->getCanCreate();
                $permission['canUpdate'] = $userRoleDataGroup->getCanUpdate();
                $permission['canDelete'] = $userRoleDataGroup->getCanDelete();
                $permissions[$userRoleDataGroup->getDataGroup()->getName()] = $permission;
            }
        }
        return $permissions;
    }

    /**
     * @param string $accessToken
     * @param string $dataGroup
     * @param string $permissionType
     * @return bool
     */
    public function isPermittedForDataGroup(string $accessToken, string $dataGroup, string $permissionType): bool {
        $permissions = $this->getPermissions($accessToken);
        return isset($permissions[$dataGroup][$permissionType]) && $permissions[$dataGroup][$permissionType] == true;
    }
}
