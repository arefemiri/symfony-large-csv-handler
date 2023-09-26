<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Username extends Constraint
{
    public $messageExisting = 'Please select another user name';
}
