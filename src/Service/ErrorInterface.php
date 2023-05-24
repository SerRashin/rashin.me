<?php

declare(strict_types=1);

namespace RashinMe\Service;

interface ErrorInterface
{
    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return array<string>
     */
    public function getDetails(): array;
}
