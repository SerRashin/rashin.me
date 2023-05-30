<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SkillData
{
    public function __construct(
        #[Assert\Length(min: 1)]
        #[Assert\NotBlank]
        public readonly string $name,
        #[Assert\NotEqualTo(value: 0, message: 'This value should not be blank or zero.')]
        public readonly int $sectionId,
        #[Assert\NotEqualTo(value: 0, message: 'This value should not be blank or zero.')]
        public readonly int $imageId,
        public readonly string $description,
    ) {
    }
}
