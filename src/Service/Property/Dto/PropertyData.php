<?php

declare(strict_types=1);

namespace RashinMe\Service\Property\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PropertyData
{
    public function __construct(
        #[Assert\Length(min:3)]
        #[Assert\Regex(pattern: "/^[A-Za-z0-9_]+$/i", message: "Invalid property key format")]
        public string $key,
        #[Assert\NotBlank]
        #[Assert\Length(min:1)]
        public string $value,
    ) {
    }
}
