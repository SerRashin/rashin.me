<?php

declare(strict_types=1);

namespace RashinMe\Service\Education\Dto;

use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class EducationData
{
    public function __construct(
        #[Assert\Length(min:3)]
        #[Assert\NotBlank]
        public readonly string $institution,
        #[Assert\Length(min:3)]
        #[Assert\NotBlank]
        public readonly string $faculty,
        #[Assert\Length(min:3)]
        #[Assert\NotBlank]
        public readonly string $specialization,
        #[Assert\NotBlank]
        public readonly DateData $from,
        public readonly ?DateData $to = null
    ) {
    }
}
