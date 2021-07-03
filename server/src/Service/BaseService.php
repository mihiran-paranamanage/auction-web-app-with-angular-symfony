<?php

namespace App\Service;

use App\Entity\EmailNotificationTemplate;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\EmailNotificationTemplateRepository;
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

    const PERMISSION_TYPE_CAN_READ = 'canRead';
    const PERMISSION_TYPE_CAN_CREATE = 'canCreate';
    const PERMISSION_TYPE_CAN_UPDATE = 'canUpdate';
    const PERMISSION_TYPE_CAN_DELETE = 'canDelete';

    const EMAIL_NOTIFICATION_ON_NEW_BID = 'New Bid Notification';

    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
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
            $this->eventPublisher = new EventPublisher();
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
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
    }

    /**
     * @param User $user
     * @param string $notificationName
     * @param array $bodyParams
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmailNotification(User $user, string $notificationName, array $bodyParams = array()) : void {
        $email = $user->getEmail();
        $emailNotificationTemplate = $this->emailNotificationTemplateRepository->findOneBy(array('name' => $notificationName));
        if ($emailNotificationTemplate instanceof EmailNotificationTemplate) {
            $subject = $emailNotificationTemplate->getSubject();
            $body = $emailNotificationTemplate->getBody();
            $body = $this->replaceBodyWithBodyParams($body, $bodyParams);
            $this->getEventPublisher()->sendEmail($email, $subject, $body);
        }
    }

    /**
     * @param string $body
     * @param array $bodyParams
     * @return string
     */
    protected function replaceBodyWithBodyParams(string $body, array $bodyParams = array()) : string {
        foreach ($bodyParams as $key => $value) {
            $body = str_replace($key, $value, $body);
        }
        return $body;
    }
}
