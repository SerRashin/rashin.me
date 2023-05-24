<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Tag;

class TagView
{
    /**
     * @param Tag $tag
     *
     * @return array<string, string>
     */
    public static function create(Tag $tag): array
    {
        return [
            'name' => $tag->getName(),
        ];
    }
}
