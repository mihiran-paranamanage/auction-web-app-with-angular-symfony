<?php

namespace App\Service;

use App\Entity\EmailNotificationTemplate;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Utility\UserRoleManager;
use App\Utility\EventPublisher;

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
    const DATA_GROUP_USER_DETAILS = 'user_details';

    const PERMISSION_TYPE_CAN_READ = 'canRead';
    const PERMISSION_TYPE_CAN_CREATE = 'canCreate';
    const PERMISSION_TYPE_CAN_UPDATE = 'canUpdate';
    const PERMISSION_TYPE_CAN_DELETE = 'canDelete';

    const EMAIL_NOTIFICATION_ON_NEW_BID = 'New Bid Notification';
    const EMAIL_NOTIFICATION_BID_CLOSED_AND_AWARDED = 'Bid Closed And Awarded Notification';
    const EMAIL_NOTIFICATION_BID_CLOSED_AND_AWARDED_WINNER = 'Bid Closed And Awarded Notification - Winner';
    const EMAIL_NOTIFICATION_MAX_AUTO_BID_EXCEEDED = 'Maximum Auto Bid Exceeded Notification';

    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $userRoleManager;
    private $eventPublisher;
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
     * @return EventPublisher
     */
    public function getEventPublisher() : EventPublisher {
        if (!($this->eventPublisher instanceof EventPublisher)) {
            $this->eventPublisher = new EventPublisher($this->emailQueueRepository, $this->configRepository);
        }
        return $this->eventPublisher;
    }

    /**
     * @param EventPublisher $eventPublisher
     */
    public function setEventPublisher(EventPublisher $eventPublisher) {
        $this->eventPublisher = $eventPublisher;
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
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository,
        EmailQueueRepository $emailQueueRepository,
        ConfigRepository $configRepository
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
        $this->emailQueueRepository = $emailQueueRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @param User $user
     * @param string $notificationName
     * @param array $params
     */
    public function pushNotificationToEmailQueue(User $user, string $notificationName, array $params = array()) : void
    {
        $email = $user->getEmail();
        $emailNotificationTemplate = $this->emailNotificationTemplateRepository->findOneBy(array('name' => $notificationName));
        if ($emailNotificationTemplate instanceof EmailNotificationTemplate) {
            $subject = $this->replaceStringWithParams($emailNotificationTemplate->getSubject(), $params);
            $body = $this->replaceStringWithParams($emailNotificationTemplate->getBody(), $params);
            $this->getEventPublisher()->pushEmailToQueue($email, $subject, $body);
        }
    }

    /**
     * @param string $string
     * @param array $params
     * @return string
     */
    protected function replaceStringWithParams(string $string, array $params = array()) : string
    {
        foreach ($params as $key => $value) {
            if (strpos($string, $key) !== false) {
                $string = str_replace($key, $value, $string);
            }
        }
        return $string;
    }
}
