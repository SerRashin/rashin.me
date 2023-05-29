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
        #[Assert\Type('int')]
        #[Assert\NotBlank]
        public readonly ?int $sectionId,
        #[Assert\Type('int')]
        #[Assert\NotBlank]
        public readonly ?int $imageId,
        public readonly string $description,
    ) {
    }
}
