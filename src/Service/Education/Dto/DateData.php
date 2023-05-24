<?php

declare(strict_types=1);

namespace RashinMe\Service\Education\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class DateData
{
    #[Assert\DateTime(format: "m")]
    #[Assert\NotBlank]
    public ?int $month;
    #[Assert\DateTime(format: "Y")]
    #[Assert\NotBlank]
    public ?int $year;

    public function __construct(
        int $month,
        int $year,
    ) {
        $this->month = $month;
        $this->year = $year;
    }
}
