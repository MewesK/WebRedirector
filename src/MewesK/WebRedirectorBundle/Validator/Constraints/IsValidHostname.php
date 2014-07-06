<?php

namespace MewesK\WebRedirectorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidHostname extends Constraint
{
    public $message = 'The string "%string%" must be a valid hostname';
}