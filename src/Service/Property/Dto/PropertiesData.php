<?php

declare(strict_types=1);

namespace RashinMe\Service\Property\Dto;

use Ser\DTORequestBundle\Attributes\MapToArrayOf;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
class PropertiesData
{
    /**
     * @var PropertyData[]
     */
    #[MapToArrayOf(PropertyData::class)]
    public array $properties = [];
}
