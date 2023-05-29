<?php

declare(strict_types=1);

namespace RashinMe\Service\Education\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class DateData
{
    public function __construct(
        #[Assert\DateTime(format: "m")]
        #[Assert\NotBlank]
        public readonly ?int $month,
        #[Assert\DateTime(format: "Y")]
        #[Assert\NotBlank]
        public readonly ?int $year,
    ) {
    }
}
