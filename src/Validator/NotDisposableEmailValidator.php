<?php

namespace Studodev\FormUtilBundle\Validator;

use Studodev\FormUtilBundle\Constant\DisposableDomainList;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotDisposableEmailValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        $exploded = explode('@', $value);
        $domain = end($exploded);

        if (!in_array($domain, DisposableDomainList::DOMAINS)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation()
        ;
    }
}
