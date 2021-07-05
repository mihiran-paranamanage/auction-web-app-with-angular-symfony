<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserBidConfig;
use App\Repository\AccessTokenRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\UserBidConfigRepository;
use App\Repository\UserRoleDataGroupRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class UserBidConfigService
 * @package App\Service
 */
class UserBidConfigService extends BaseService
{
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $userBidConfigRepository;
    private $emailQueueRepository;
    private $configRepository;

    /**
     * UserBidConfigService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserBidConfigRepository $userBidConfigRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserBidConfigRepository $userBidConfigRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository,
        EmailQueueRepository $emailQueueRepository,
        ConfigRepository $configRepository
    ) {
        parent::__construct(
            $accessTokenRepository,
            $userRoleDataGroupRepository,
            $emailNotificationTemplateRepository,
            $this->emailQueueRepository = $emailQueueRepository,
            $this->configRepository = $configRepository
        );
        $this->userBidConfigRepository = $userBidConfigRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
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
                $userBidConfig = new UserBidConfig();
                $userBidConfig->setUser($user);
                $userBidConfig->setMaxBidAmount(0);
                $userBidConfig->setCurrentBidAmount(0);
                $userBidConfig->setNotifyPercentage(100);
                $userBidConfig->setIsAutoBidEnabled(0);
                $userBidConfig->setIsMaxBidExceedNotified(0);
                return $this->userBidConfigRepository->saveUserBidConfig($userBidConfig);
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
                $userBidConfig->setCurrentBidAmount(0);
                $userBidConfig->setIsMaxBidExceedNotified(0);
            }
        } else {
            throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
        }
        $userBidConfig->setUser($user);
        $userBidConfig->setMaxBidAmount($params['maxBidAmount']);
        $userBidConfig->setNotifyPercentage($params['notifyPercentage']);
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
            'userName' => $userBidConfig->getUser()->getUsername(),
            'maxBidAmount' => $userBidConfig->getMaxBidAmount(),
            'currentBidAmount' => $userBidConfig->getCurrentBidAmount(),
            'notifyPercentage' => $userBidConfig->getNotifyPercentage(),
            'isAutoBidEnabled' => $userBidConfig->getIsAutoBidEnabled(),
            'isMaxBidExceedNotified' => $userBidConfig->getIsMaxBidExceedNotified()
        );
    }

    /**
     * @param UserBidConfig $userBidConfig
     */
    public function checkMaxAutoBidAmountStatus(UserBidConfig $userBidConfig) : void
    {
        $maxBidAmount = $userBidConfig->getMaxBidAmount();
        $currentBidAmount = $userBidConfig->getCurrentBidAmount();
        if ($maxBidAmount > 0 && $maxBidAmount <= $currentBidAmount) {
            if ($userBidConfig->getIsAutoBidEnabled() && !$userBidConfig->getIsMaxBidExceedNotified()) {
                $userBidConfig->setIsMaxBidExceedNotified(true);
                $this->pushMaxAutoBidExceededNotificationToEmailQueue($userBidConfig);
                $this->userBidConfigRepository->saveUserBidConfig($userBidConfig);
            }
        } else {
            if ($userBidConfig->getIsMaxBidExceedNotified()) {
                $userBidConfig->setIsMaxBidExceedNotified(false);
                $this->userBidConfigRepository->saveUserBidConfig($userBidConfig);
            }
        }
    }

    /**
     * @param UserBidConfig $userBidConfig
     */
    public function pushMaxAutoBidExceededNotificationToEmailQueue(UserBidConfig $userBidConfig) : void
    {
        $user = $userBidConfig->getUser();
        $params = array(
            '#recipientFirstName#' => $user->getFirstName(),
            '#recipientLastName#' => $user->getLastName(),
            '#maxBidAmount#' => $userBidConfig->getMaxBidAmount(),
            '#currentBidAmount#' => $userBidConfig->getCurrentBidAmount()
        );
        $this->pushNotificationToEmailQueue($user, BaseService::EMAIL_NOTIFICATION_MAX_AUTO_BID_EXCEEDED, $params);
    }
}
