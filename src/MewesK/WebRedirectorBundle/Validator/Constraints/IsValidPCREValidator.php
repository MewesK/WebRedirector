<?php

namespace MewesK\WebRedirectorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidPCREValidator extends ConstraintValidator
{
    const REGEX_PCRE = '/^\/.*\/(.*)$/';
    const REGEX_OPTIONS = '/^[imsx]{0,4}$/';

    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        /** @var $constraint IsValidPCRE */

        if (preg_match(self::REGEX_PCRE, $value, $matches)) {
            if (!preg_match(self::REGEX_OPTIONS, $matches[1], $matches)) {
                $this->context->addViolation(
                    $constraint->messageOptions,
                    array('%string%' => $value)
                );
            }
        } else {
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}