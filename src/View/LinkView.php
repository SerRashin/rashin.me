<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Link;

class LinkView
{
    /**
     * @param Link $link
     *
     * @return array<string, mixed>
     */
    public static function create(Link $link): array
    {
        return [
            'id' => $link->getId(),
            'title' => $link->getTitle(),
            'url' => $link->getUrl(),
        ];
    }
}
