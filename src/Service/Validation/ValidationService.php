<?php

declare(strict_types=1);

namespace RashinMe\Service\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validation service
 */
class ValidationService implements ValidationServiceInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint|array $constraints = null): ?ValidationError
    {
        $violations = $this->validator->validate($value, $constraints);

        if (count($violations) === 0) {
            return null;
        }

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = (string) $violation->getMessage();
        }

        return new ValidationError('Validation Error', $errors);
    }
}
