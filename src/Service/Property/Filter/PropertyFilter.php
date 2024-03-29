<?php

declare(strict_types=1);

namespace RashinMe\Service\Property\Filter;

class PropertyFilter
{
    /**
     * @var string[]
     */
    public array $fields = [];


    /**
     * @param string[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }
}
