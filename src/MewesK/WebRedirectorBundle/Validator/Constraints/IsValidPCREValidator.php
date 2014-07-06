<?php

namespace MewesK\WebRedirectorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidPCREValidator extends ConstraintValidator
{
    private static $REGEX_PCRE = '/^\/.*\/[imox]{0,4}$/';

    public function validate($value, Constraint $constraint)
    {
        if (!empty($value) && !preg_match(self::$REGEX_PCRE, $value, $matches)) {
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}