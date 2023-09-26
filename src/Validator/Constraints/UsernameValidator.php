<?php

namespace App\Validator\Constraints;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UsernameValidator extends ConstraintValidator
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed                                   $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Username) {
            throw new UnexpectedTypeException($constraint, Username::class);
        }

        if ($value !== NULL && !is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (empty($value)) {
            return;
        }

        if ($this->checkUserExisting($value)) {
            $this->context->buildViolation($constraint->messageExisting)->addViolation();
        }
    }

    /**
     * @param $username
     *
     * @return bool
     */
    private function checkUserExisting($username)
    {
        return $this->em->getRepository(User::class)->findOneByUsername($username) !== null;
    }
}
