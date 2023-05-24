<?php

declare(strict_types=1);

namespace RashinMe\Service;

class Error implements ErrorInterface
{
    /**
     * @param string $message
     * @param array<string> $details
     */
    public function __construct(
        private readonly string $message,
        private readonly array $details = [],
    ) {
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array<string>
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
