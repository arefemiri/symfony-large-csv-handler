<?php

namespace App\Validator\Constraints;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmailExistingValidator extends ConstraintValidator
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * EmailExistingValidator constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EmailExisting) {
            throw new UnexpectedTypeException($constraint, EmailExisting::class);
        }

        if ($value !== NULL && !is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (empty($value)) {
            return;
        }

        if ($this->checkEmailExisting($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

    /**
     * @param $email
     *
     * @return bool
     */
    private function checkEmailExisting($email)
    {
        return (bool)$this->em->getRepository(User::class)->findOneByEmail($email);
    }
}
