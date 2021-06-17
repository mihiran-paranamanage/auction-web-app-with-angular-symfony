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