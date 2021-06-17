<?php

namespace App\Service;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
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
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $userRepository;

    /**
     * AccessTokenService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserRepository $userRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserRepository $userRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository
    ) {
        parent::__construct($accessTokenRepository, $userRoleDataGroupRepository);
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
    }

    /**
     * @param string $username
     * @return AccessToken
     */
    public function getAccessToken(string $username) : AccessToken
    {
        $user = $this->userRepository->findOneBy(array('username' => $username));
        if ($user instanceof User) {
            $accessToken = $this->accessTokenRepository->findOneBy(array('user' => $user));
            if ($accessToken instanceof AccessToken) {
                return $accessToken;
            } else {
                throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
            }
        } else {
            throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
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