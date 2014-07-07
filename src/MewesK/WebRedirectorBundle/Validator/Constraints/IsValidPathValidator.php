<?php

namespace MewesK\WebRedirectorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidPathValidator extends ConstraintValidator
{
    const REGEX_PATH = '/^\/.*$/';

    public function validate($value, Constraint $constraint)
    {
        /** @var $constraint IsValidPath */
        if (!empty($value) && !preg_match(self::REGEX_PATH, $value, $matches)) {
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}