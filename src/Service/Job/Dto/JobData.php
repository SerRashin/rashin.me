<?php

declare(strict_types=1);

namespace RashinMe\Service\Job\Dto;

use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class JobData
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public CompanyData $company;

    #[Assert\Length(min:10)]
    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    public string $type;

    #[Assert\NotBlank]
    public DateData $from;

    public ?DateData $to;

    public function __construct(
        string $name,
        string $description,
        string $type,
        CompanyData $company,
        DateData $from,
        ?DateData $to = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->company = $company;
        $this->from = $from;
        $this->to = $to;
    }
}
