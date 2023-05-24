<?php

declare(strict_types=1);

namespace RashinMe\Service\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * Validation service
 */
interface ValidationServiceInterface
{
    /**
     * Validate data
     *
     * @param mixed                 $value object for validation
     * @param Constraint|Constraint[] $constraints
     *
     * @return ValidationError|null
     */
    public function validate(mixed $value, Constraint|array $constraints = null): ?ValidationError;
}
