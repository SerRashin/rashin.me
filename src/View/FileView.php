<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\File;

class FileView
{
    /**
     * @param File $file
     *
     * @return array<string, int|string>
     */
    public static function create(File $file): array
    {
        return [
            'id' => $file->getId(),
            'name' => $file->getName(),
            'path' => $file->getPath(),
            'src' => $file->getPath() . $file->getName(),
            'mimeType' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }
}
