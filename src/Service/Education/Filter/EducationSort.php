<?php

declare(strict_types=1);

namespace RashinMe\Service\Education\Filter;

class EducationSort
{
    public const DEFAULT_SORT = 'id';
    public const DEFAULT_ORDER = 'ASC';

    private const AVAILABLE_SORT = [
        'id',
        'institution',
        'faculty',
        'specialization'
    ];

    private const AVAILABLE_ORDER = [
        'ASC',
        'DESC',
    ];

    public string $field = self::DEFAULT_SORT;
    public string $order = self::DEFAULT_ORDER;

    public function __construct(
        string $field,
        string $order
    ) {
        if (in_array($field, self::AVAILABLE_SORT)) {
            $this->field = $field;
        }

        if (in_array(strtoupper($order), self::AVAILABLE_ORDER)) {
            $this->order = $order;
        }
    }
}
