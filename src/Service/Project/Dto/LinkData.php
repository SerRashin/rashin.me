<?php

declare(strict_types=1);

namespace RashinMe\Service\Project\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LinkData
{
    public ?int $id;

    public function __construct(
        #[Assert\Length(min: 3)]
        #[Assert\NotBlank]
        public readonly string $title,
        #[Assert\Length(min: 5)]
        #[Assert\NotBlank]
        public readonly string $url,
    ) {
    }
}
