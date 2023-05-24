<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Property;

class PropertyView
{
    /**
     * @param Property $property
     *
     * @return array<string, int|string>
     */
    public static function create(Property $property): array
    {
        return [
            'key' => $property->getKey(),
            'value' => $property->getValue(),
        ];
    }
}
