<?php

namespace App\Service;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class AccessTokenService
 * @package App\Service
 */
class AccessTokenService extends BaseService
{
    private $accessTokenRepository;
    private $userRepository;

    /**
     * AccessTokenController constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserRepository $userRepository
    ) {
        parent::__construct($accessTokenRepository);
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
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