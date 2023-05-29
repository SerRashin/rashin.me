<?php

declare(strict_types=1);

namespace RashinMe\Service\Job\Dto;

use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class JobData
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,
        #[Assert\NotBlank]
        public readonly CompanyData $company,
        #[Assert\Length(min:10)]
        #[Assert\NotBlank]
        public readonly string $description,
        #[Assert\NotBlank]
        public readonly string $type,
        #[Assert\NotBlank]
        public readonly DateData $from,
        public readonly ?DateData $to = null,
    ) {
    }
}
