<?php

declare(strict_types=1);

namespace RashinMe\Service\Project\Dto;

use Ser\DtoRequestBundle\Attributes\MapToArrayOf;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class ProjectData
{
    /**
     * @var string[]
     */
    #[Assert\All([
        new Assert\NotBlank(),
        new Assert\Type("alnum"),
    ])]
    public array $tags;

    /**
     * @var LinkData[]
     */
    #[MapToArrayOf(LinkData::class)]
    public array $links = [];

    public function __construct(
        #[Assert\Length(min: 6)]
        #[Assert\NotBlank]
        public readonly string $name,
        #[Assert\Length(min: 15)]
        #[Assert\NotBlank]
        public readonly string $description,
        #[Assert\Type('int')]
        #[Assert\NotBlank]
        public readonly ?int $imageId,
    ) {
    }
}
