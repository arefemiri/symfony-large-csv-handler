<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailExisting extends Constraint
{
    public $message  = 'Email address is already in use.';
}
