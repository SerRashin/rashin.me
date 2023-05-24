<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\File;

class ImageView
{
    /**
     * @param ?File $file
     *
     * @return array<string, int|string>
     */
    public static function create(?File $file): array
    {
        if ($file === null) {
            return [];
        }

        return [
            'id' => $file->getId(),
            'src' => $file->getPath() . $file->getName(),
        ];
    }
}
