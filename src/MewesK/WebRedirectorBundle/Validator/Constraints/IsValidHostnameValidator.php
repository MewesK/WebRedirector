<?php

namespace MewesK\WebRedirectorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidHostnameValidator extends ConstraintValidator
{
    const REGEX_IP = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
    const REGEX_HOSTNAME = '/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/';

    public function validate($value, Constraint $constraint)
    {
        /** @var $constraint IsValidHostname */
        if (!empty($value) && !(preg_match(self::REGEX_IP, $value, $matches) || preg_match(self::REGEX_HOSTNAME, $value, $matches))) {
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}