<?php

declare(strict_types=1);

namespace RashinMe\Entity;

class Tag
{
    /**
     * @var int
     */
    private int $id;

    public function __construct(
        private string $name
    ) {
        $this->id = 0;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
