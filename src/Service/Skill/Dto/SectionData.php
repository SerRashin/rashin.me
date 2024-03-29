<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SectionData
{
    public function __construct(
        #[Assert\Length(min: 3)]
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}
