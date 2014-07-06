<?php

namespace MewesK\WebRedirectorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidPCRE extends Constraint
{
    public $message = 'The string "%string%" must be a valid Perl Compatible Regular Expression';
}