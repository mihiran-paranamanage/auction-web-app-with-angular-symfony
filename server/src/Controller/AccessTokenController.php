<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
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
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $accessToken;

    /**
     * AccessTokenController constructor.
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
     * @return AccessTokenService
     */
    public function getAccessTokenService() : AccessTokenService {
        if (!($this->accessToken instanceof AccessTokenService)) {
            $this->accessToken = new AccessTokenService(
                $this->accessTokenRepository,
                $this->userRepository,
                $this->userRoleDataGroupRepository,
                $this->emailNotificationTemplateRepository,
                $this->emailQueueRepository,
                $this->configRepository
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
     * @api {get} http://localhost:8001/api/accessToken Access Token - Get
     * @apiDescription Get Access Token
     * @apiName getAccessToken
     * @apiGroup AUTHENTICATION
     * @apiSubGroup Access Token
     * @apiParam {String} username - Username
     * @apiParam {String} password - Password
     * @apiSampleRequest http://localhost:8001/api/accessToken
     * @apiSuccess {Json} Object Object containing access token data
     * @apiSuccessExample Success-Response:
     *  {
     *    "id": 1,
     *    "userId": 1,
     *    "username": "admin1",
     *    "token": "af874ho9s8dfush6"
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     * @apiError (404) NotFound Not Found
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/accessToken", name="getAccessToken", methods={"GET"})
     */
    public function getAccessToken(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $username = $request->get('username');
        $password = $request->get('password');
        $accessToken = $this->getAccessTokenService()->getAccessToken($username, $password);
        return new JsonResponse($this->getAccessTokenService()->formatAccessTokenResponse($accessToken), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('username', v::stringVal()->notEmpty(), true),
            v::key('password', v::stringVal()->notEmpty(), true)
        );
        $this->validate($validator, $request->query->all());
    }
}
