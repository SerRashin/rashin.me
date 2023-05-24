<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SkillData
{
    #[Assert\Length(min: 1)]
    #[Assert\NotBlank]
    public string $name;

    #[Assert\Type('int')]
    #[Assert\NotBlank]
    public int $sectionId;

    #[Assert\Type('int')]
    #[Assert\NotBlank]
    public int $imageId;

    #[Assert\Length(min: 1)]
    #[Assert\NotBlank]
    public string $description;
}
