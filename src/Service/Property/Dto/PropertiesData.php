<?php

declare(strict_types=1);

namespace RashinMe\Service\Property\Dto;

use Ser\DtoRequestBundle\Attributes\MapToArrayOf;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class PropertiesData
{
    /**
     * @param PropertyData[] $properties
     */
    public function __construct(
        #[MapToArrayOf(PropertyData::class)]
        public readonly array $properties,
    ) {
    }
}
