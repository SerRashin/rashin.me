<?php

declare(strict_types=1);

namespace RashinMe\Entity;

class Property
{
    public function __construct(
        private string $key,
        private string $value,
    ) {
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setName(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
    * @param string $value
    */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
