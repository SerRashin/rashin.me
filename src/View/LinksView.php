<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Link;

class LinksView
{
    /**
     * @param Link[] $links
     *
     * @return array<int, mixed>
     */
    public static function create(array $links): array
    {
        $arr = [];

        foreach ($links as $link) {
            $arr[] = LinkView::create($link);
        }

        return $arr;
    }
}
