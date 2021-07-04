<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Service\BaseService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class UserController
 * @package App\Controller
 * @Route(path="/api")
 */
class UserController extends BaseController
{
    const INCLUDE_BIDS = 'bids';
    const INCLUDE_AWARDED_ITEMS = 'awardedItems';

    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $bidRepository;
    private $itemRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $userService;

    /**
     * UserController constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param BidRepository $bidRepository
     * @param ItemRepository $itemRepository
     * @param UserRepository $userRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        BidRepository $bidRepository,
        ItemRepository $itemRepository,
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
        $this->bidRepository = $bidRepository;
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
    }

    /**
     * @return UserService
     */
    public function getUserService() : UserService {
        if (!($this->userService instanceof UserService)) {
            $this->userService = new UserService(
                $this->accessTokenRepository,
                $this->itemRepository,
                $this->userRoleDataGroupRepository,
                $this->bidRepository,
                $this->emailNotificationTemplateRepository,
                $this->userRepository,
                $this->emailQueueRepository,
                $this->configRepository
            );
        }
        return $this->userService;
    }

    /**
     * @param UserService $userService
     */
    public function setUserService(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/users/userDetails", name="getUserDetails", methods={"GET"})
     */
    public function getUserDetails(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $includeParams = $request->get('include') ? explode(',', $request->get('include')) : array();
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_USER_DETAILS, BaseService::PERMISSION_TYPE_CAN_READ);
        $user = $this->getUser($accessToken);
        return new JsonResponse($this->getUserService()->formatUserDetailsResponse($user, $includeParams), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/users/userDetails", name="updateUserDetails", methods={"PUT"})
     */
    public function updateUserDetails(Request $request): JsonResponse
    {
        $this->validatePutRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken'], BaseService::DATA_GROUP_USER_DETAILS, BaseService::PERMISSION_TYPE_CAN_UPDATE);
        $user = $this->getUserService()->updateUserDetails($params);
        return new JsonResponse($this->getUserService()->formatUserDetailsResponse($user, array()), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $includeValues = array(self::INCLUDE_BIDS, self::INCLUDE_AWARDED_ITEMS);
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('include', v::stringType()
                ->notEmpty()
                ->noWhitespace()
                ->callback(function ($include) use ($includeValues) {
                    $includeParams  = explode(',', $include);
                    return v::arrayVal()->each(v::in($includeValues))->validate($includeParams);
                }), false)
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
            v::key('firstName', v::stringVal()->notEmpty(), true),
            v::key('lastName', v::stringVal()->notEmpty(), true),
            v::key('email', v::stringVal()->notEmpty()->email(), true),
            v::key('password', v::stringVal()->notEmpty(), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
