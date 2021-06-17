<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserBidConfig;
use App\Repository\AccessTokenRepository;
use App\Repository\UserBidConfigRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class UserBidConfigService
 * @package App\Service
 */
class UserBidConfigService extends BaseService
{
    private $userBidConfigRepository;

    /**
     * UserBidConfigService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserBidConfigRepository $userBidConfigRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserBidConfigRepository $userBidConfigRepository
    ) {
        parent::__construct($accessTokenRepository);
        $this->userBidConfigRepository = $userBidConfigRepository;
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
        $userBidConfig = $this->getUserBidConfig($params['accessToken']);
        $user = $this->getUser($params['accessToken']);
        $userBidConfig->setUser($user);
        $userBidConfig->setMaxBidAmount($params['maxBidAmount']);
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
            'maxBidAmount' => $userBidConfig->getMaxBidAmount()
        );
    }
}