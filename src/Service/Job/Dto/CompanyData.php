<?php

declare(strict_types=1);

namespace RashinMe\Service\Job\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CompanyData
{
    #[Assert\Length(min: 5)]
    #[Assert\NotBlank]
    public string $name;

    #[Assert\Url(
        protocols: [
            'http',
            'https'
        ],
    )]
    #[Assert\NotBlank]
    public string $url;

    public function __construct(
        string $name,
        string $url,
    ) {
        $this->name = $name;
        $this->url = $url;
    }
}
