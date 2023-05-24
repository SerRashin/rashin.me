<?php

declare(strict_types=1);

namespace RashinMe\Service\Education\Dto;

use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class EducationData
{
    #[Assert\Length(min:3)]
    #[Assert\NotBlank]
    public string $institution;

    #[Assert\Length(min:3)]
    #[Assert\NotBlank]
    public string $faculty;

    #[Assert\Length(min:3)]
    #[Assert\NotBlank]
    public string $specialization;

    #[Assert\NotBlank]
    public DateData $from;

    public ?DateData $to;

    public function __construct(
        string $institution,
        string $faculty,
        string $specialization,
        DateData $from,
        ?DateData $to = null
    ) {
        $this->institution = $institution;
        $this->faculty = $faculty;
        $this->specialization = $specialization;
        $this->from = $from;
        $this->to = $to;
    }
}
