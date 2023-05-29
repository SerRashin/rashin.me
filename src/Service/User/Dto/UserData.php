<?php

declare(strict_types=1);

namespace RashinMe\Service\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserData
{
    public function __construct(
        #[Assert\Length(min: 3, max: 75)]
        #[Assert\NotBlank]
        public string $firstName,
        #[Assert\Length(min: 3, max: 75)]
        #[Assert\NotBlank]
        public string $lastName,
        #[Assert\Length(min: 6)]
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}

