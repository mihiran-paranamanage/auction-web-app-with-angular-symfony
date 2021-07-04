<?php

namespace App\Service;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleDataGroupRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class AccessTokenService
 * @package App\Service
 */
class AccessTokenService extends BaseService
{
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $configRepository;

    /**
     * AccessTokenService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserRepository $userRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserRepository $userRepository,
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
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
    }

    /**
     * @param string $username
     * @param string $password
     * @return AccessToken
     */
    public function getAccessToken(string $username, string $password) : AccessToken
    {
        $user = $this->userRepository->findOneBy(array('username' => $username));
        if ($user instanceof User) {
            $accessToken = $this->accessTokenRepository->findOneBy(array('user' => $user));
            if ($this->isPasswordValid($user, $password) && $accessToken instanceof AccessToken) {
                return $accessToken;
            } else {
                throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
            }
        } else {
            throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    protected function isPasswordValid(User $user, string $password) : bool
    {
        return $user->getPassword() == md5($password);
    }

    /**
     * @param AccessToken $accessToken
     * @return array
     */
    public function formatAccessTokenResponse(AccessToken $accessToken) : array
    {
        return array(
            'id' => $accessToken->getId(),
            'username' => $accessToken->getUser()->getUsername(),
            'token' => $accessToken->getToken()
        );
    }
}
