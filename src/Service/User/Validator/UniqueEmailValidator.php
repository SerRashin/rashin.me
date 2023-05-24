<?php

declare(strict_types=1);

namespace RashinMe\Service\User\Validator;

use RashinMe\Service\User\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        // @phpstan-ignore-next-line
        $user = $this->userRepository->findOneByEmail($value);

        if ($user !== null) {
            $this->context->addViolation(
                // @phpstan-ignore-next-line
                $constraint->message,
                [
                    'value' => $value,
                ],
            );
        }
    }
}
