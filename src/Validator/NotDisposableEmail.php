<?php

namespace Studodev\FormUtilBundle\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class NotDisposableEmail extends Constraint
{
    public string $message = 'The e-mail {{ value }} use a disposable domain.';
}
