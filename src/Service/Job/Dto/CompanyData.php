<?php

declare(strict_types=1);

namespace RashinMe\Service\Job\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CompanyData
{
    public function __construct(
        #[Assert\Length(min: 5)]
        #[Assert\NotBlank]
        public readonly string $name,
        #[Assert\Url(
            protocols: [
                'http',
                'https'
            ],
        )]
        #[Assert\NotBlank]
        public readonly string $url,
    ) {
    }
}
