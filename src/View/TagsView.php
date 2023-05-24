<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Tag;

class TagsView
{
    /**
     * @param Tag[] $tags
     *
     * @return array<int, string>
     */
    public static function create(array $tags): array
    {
        $arr = [];

        foreach ($tags as $tag) {
            $arr[] = $tag->getName();
        }

        return $arr;
    }
}
