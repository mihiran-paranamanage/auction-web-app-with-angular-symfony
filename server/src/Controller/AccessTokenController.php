<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Service\AccessTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class AccessTokenController
 * @package App\Controller
 * @Route(path="/api")
 */
class AccessTokenController extends BaseController
{
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $userRepository;
    private $accessToken;

    /**
     * AccessTokenController constructor.
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
     * @return AccessTokenService
     */
    public function getAccessTokenService() : AccessTokenService {
        if (!($this->accessToken instanceof AccessTokenService)) {
            $this->accessToken = new AccessTokenService(
                $this->accessTokenRepository,
                $this->userRepository,
                $this->userRoleDataGroupRepository
            );
        }
        return $this->accessToken;
    }

    /**
     * @param AccessTokenService $accessToken
     */
    public function setAccessTokenService(AccessTokenService $accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/accessToken", name="getAccessToken", methods={"GET"})
     */
    public function getAccessToken(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $username = $request->get('username');
        $accessToken = $this->getAccessTokenService()->getAccessToken($username);
        return new JsonResponse($this->getAccessTokenService()->formatAccessTokenResponse($accessToken), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $validator = v::key('username', v::stringVal()->notEmpty(), true);
        $this->validate($validator, $request->query->all());
    }
}