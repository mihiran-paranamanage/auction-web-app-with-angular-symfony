<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\UserBidConfigRepository;
use App\Service\UserBidConfigService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class UserBidConfigController
 * @package App\Controller
 * @Route(path="/api")
 */
class UserBidConfigController extends BaseController
{
    private $accessTokenRepository;
    private $userBidConfigRepository;
    private $userBidConfigService;

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
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userBidConfigRepository = $userBidConfigRepository;
    }

    /**
     * @return UserBidConfigService
     */
    public function getUserBidConfigService() : UserBidConfigService {
        if (!($this->userBidConfigService instanceof UserBidConfigService)) {
            $this->userBidConfigService = new UserBidConfigService($this->accessTokenRepository, $this->userBidConfigRepository);
        }
        return $this->userBidConfigService;
    }

    /**
     * @param UserBidConfigService $userBidConfigService
     */
    public function setUserBidConfigService(UserBidConfigService $userBidConfigService) {
        $this->userBidConfigService = $userBidConfigService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/autoBidConfig", name="getUserBidConfig", methods={"GET"})
     */
    public function getUserBidConfig(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken);
        $userBidConfig = $this->getUserBidConfigService()->getUserBidConfig($accessToken);
        return new JsonResponse($this->getUserBidConfigService()->formatUserBidConfigResponse($userBidConfig), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/autoBidConfig", name="saveUserBidConfig", methods={"PUT"})
     */
    public function saveUserBidConfig(Request $request): JsonResponse
    {
        $this->validatePutRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken']);
        $userBidConfig = $this->getUserBidConfigService()->saveUserBidConfig($params);
        return new JsonResponse($this->getUserBidConfigService()->formatUserBidConfigResponse($userBidConfig), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true)
        );
        $this->validate($validator, $request->query->all());
    }

    /**
     * @param Request $request
     */
    protected function validatePutRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('maxBidAmount', v::intVal()->positive(), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}