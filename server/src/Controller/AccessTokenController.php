<?php

namespace App\Controller;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class AccessTokenController
 * @package App\Controller
 * @Route(path="/api")
 */
class AccessTokenController extends BaseController
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
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
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
        $accessToken = $this->getAccessTokenByUsername($username);
        return new JsonResponse($this->formatGetResponse($accessToken), Response::HTTP_OK);
    }

    /**
     * @param string $username
     * @return AccessToken
     */
    protected function getAccessTokenByUsername(string $username) : AccessToken
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
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $validator = v::key('username', v::stringVal()->notEmpty(), true);
        $this->validate($validator, $request->query->all());
    }

    /**
     * @param AccessToken $accessToken
     * @return array
     */
    protected function formatGetResponse(AccessToken $accessToken) : array
    {
        return array(
            'id' => $accessToken->getId(),
            'username' => $accessToken->getUser()->getUsername(),
            'token' => $accessToken->getToken()
        );
    }
}