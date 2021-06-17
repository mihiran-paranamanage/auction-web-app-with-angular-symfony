<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserBidConfig;
use App\Repository\AccessTokenRepository;
use App\Repository\UserBidConfigRepository;
use App\Repository\UserRoleDataGroupRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class UserBidConfigService
 * @package App\Service
 */
class UserBidConfigService extends BaseService
{
    private $userRoleDataGroupRepository;
    private $userBidConfigRepository;

    /**
     * UserBidConfigService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserBidConfigRepository $userBidConfigRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserBidConfigRepository $userBidConfigRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository
    ) {
        parent::__construct($accessTokenRepository, $userRoleDataGroupRepository);
        $this->userBidConfigRepository = $userBidConfigRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
    }

    /**
     * @param string $accessToken
     * @return UserBidConfig
     */
    public function getUserBidConfig(string $accessToken) : UserBidConfig
    {
        $user = $this->getUser($accessToken);
        if ($user instanceof User) {
            $userBidConfig = $this->userBidConfigRepository->findOneBy(array('user' => $user));
            if ($userBidConfig instanceof UserBidConfig) {
                return $userBidConfig;
            } else {
                throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            }
        } else {
            throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
        }
    }

    /**
     * @param array $params
     * @return UserBidConfig
     */
    public function saveUserBidConfig(array $params) : UserBidConfig {
        $user = $this->getUser($params['accessToken']);
        if ($user instanceof User) {
            $userBidConfig = $this->userBidConfigRepository->findOneBy(array('user' => $user));
            if (!($userBidConfig instanceof UserBidConfig)) {
                $userBidConfig = new UserBidConfig();
            }
        } else {
            throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
        }
        $userBidConfig->setUser($user);
        $userBidConfig->setMaxBidAmount($params['maxBidAmount']);
        $userBidConfig->setIsAutoBidEnabled(!!$params['isAutoBidEnabled']);
        return $this->userBidConfigRepository->saveUserBidConfig($userBidConfig);
    }

    /**
     * @param UserBidConfig $userBidConfig
     * @return array
     */
    public function formatUserBidConfigResponse(UserBidConfig $userBidConfig) : array
    {
        return array(
            'id' => $userBidConfig->getId(),
            'userId' => $userBidConfig->getUser()->getId(),
            'maxBidAmount' => $userBidConfig->getMaxBidAmount(),
            'isAutoBidEnabled' => $userBidConfig->getIsAutoBidEnabled()
        );
    }
}