<?php

declare(strict_types=1);

namespace RashinMe\Service\Validation;

use RashinMe\Service\Error;

final class ValidationError extends Error
{
    /**
     * @param string $message
     * @param array<string> $details
     */
    public function __construct(
        string $message,
        array $details,
    ) {
        parent::__construct($message, $details);
    }
}
