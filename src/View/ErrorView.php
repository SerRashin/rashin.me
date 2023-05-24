<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Service\ErrorInterface;

class ErrorView
{
    /**
     * @param ErrorInterface $error
     *
     * @return array<string, mixed>
     */
    public static function create(ErrorInterface $error): array
    {
        return [
            'message' => $error->getMessage(),
            'details' => $error->getDetails(),
        ];
    }
}
