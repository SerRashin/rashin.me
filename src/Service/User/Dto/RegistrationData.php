<?php

declare(strict_types=1);

namespace RashinMe\Service\User\Dto;

use RashinMe\Service\User\Constraint\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationData
{
    #[Assert\Length(min: 3, max: 75)]
    #[Assert\NotBlank]
    public string $firstName;

    #[Assert\Length(min: 3, max: 75)]
    #[Assert\NotBlank]
    public string $lastName;

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[UniqueEmail]
        public readonly string $email,
        #[Assert\NotBlank]
        public readonly string $password
    ) {
    }
}
