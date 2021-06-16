<?php

namespace App\Controller;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController
{
    /**
     * @param Validatable $validator
     * @param array $parameters
     */
    protected function validate(Validatable $validator, array $parameters) {
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
