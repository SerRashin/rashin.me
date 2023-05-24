<?php

declare(strict_types=1);

namespace RashinMe\Service\User\Constraint;

use Attribute;
use RashinMe\Service\User\Validator\UniqueEmailValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class UniqueEmail extends Constraint
{
    public string $message = 'Email address already exists.';

    /**
     * @inheritDoc
     */
    public function validatedBy(): string
    {
        return UniqueEmailValidator::class;
    }
}
