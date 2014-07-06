<?php

namespace MewesK\WebRedirectorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidPath extends Constraint
{
    public $message = 'The string "%string%" must start with a "/"';
}