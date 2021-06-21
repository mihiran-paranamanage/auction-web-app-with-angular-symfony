<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\BaseService;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends BaseService
{
    /**
     * @param string $accessToken
     * @param string $dataGroup
     * @param string $permissionType
     */
    protected function checkAuthorization(string $accessToken, string $dataGroup, string $permissionType) : void {
        $user = $this->getUser($accessToken);
        if ($user instanceof User) {
            $isPermittedForDataGroup = $this->getUserRoleManager()->isPermittedForDataGroup($accessToken, $dataGroup, $permissionType);
            if (!$isPermittedForDataGroup) {
                throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
            }
        } else {
            throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
        }
    }

    /**
     * @param Validatable $validator
     * @param array $parameters
     */
    protected function validate(Validatable $validator, array $parameters) : void {
        try {
            $validator->assert($parameters);
        } catch (NestedValidationException $e) {
            throw new BadRequestException(
                preg_replace("/".PHP_EOL."/", "\n",
                    $e->getFullMessage())
            );
        }
    }
}
