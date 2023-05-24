<?php

declare(strict_types=1);

namespace RashinMe\Service\Response\Dto;

final class SystemMessage
{
    /**
     * @param int $code
     * @param string $message
     */
    public function __construct(
        public readonly int $code,
        public readonly string $message,
    ) {
    }
}
