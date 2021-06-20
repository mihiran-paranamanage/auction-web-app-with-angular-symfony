<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class UserRoleDataGroupController
 * @package App\Controller
 * @Route(path="/api")
 */
class UserRoleDataGroupController extends BaseController
{
    /**
     * @api {get} http://localhost:8001/api/permissions Permissions - Get
     * @apiDescription Get Permissions
     * @apiName getPermissions
     * @apiGroup AUTHORIZATION
     * @apiSubGroup Permissions
     * @apiParam {String} accessToken - Access Token
     * @apiSampleRequest http://localhost:8001/api/permissions
     * @apiSuccess {Json} Object Object containing permission data
     * @apiSuccessExample Success-Response:
     *  {
     *    "item":{
     *      "canRead":true,
     *      "canCreate":true,
     *      "canUpdate":true,
     *      "canDelete":true
     *    },
     *    "bid":{
     *      "canRead":true,
     *      "canCreate":true,
     *      "canUpdate":true,
     *      "canDelete":true
     *    },
     *    "bid_history":{
     *      "canRead":true,
     *      "canCreate":true,
     *      "canUpdate":true,
     *      "canDelete":true
     *    },
     *    "configure_auto_bid":{
     *      "canRead":true,
     *      "canCreate":true,
     *      "canUpdate":true,
     *      "canDelete":true
     *    },
     *    "admin_dashboard":{
     *      "canRead":true,
     *      "canCreate":true,
     *      "canUpdate":true,
     *      "canDelete":true
     *    }
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/permissions", name="getPermissions", methods={"GET"})
     */
    public function getPermissions(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        return new JsonResponse($this->getUserRoleManager()->getPermissions($accessToken), Response::HTTP_OK);
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
}
