<?php

declare(strict_types=1);

namespace RashinMe\View;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Property;

class PropertiesView
{
    /**
     * @param Collection<int, Property> $collection
     *
     * @return array<int, mixed>
     */
    public static function create(Collection $collection): array
    {
        $arr = [];

        foreach ($collection as $item) {
            $arr[] = PropertyView::create($item);
        }

        return $arr;
    }
}
