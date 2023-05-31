<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Education\Filter\EducationSort;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectEducationSort implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<EducationSort>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== EducationSort::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $sortJson = $request->query->get('sort') ?? '';

        $field = EducationSort::DEFAULT_SORT;
        $order = EducationSort::DEFAULT_ORDER;

        if (!empty($sortJson)) {
            /**
             * @var object{field: string, order: string}|null $sort
             */
            $sort = json_decode((string)$sortJson, false);

            $field = $sort?->field ?? EducationSort::DEFAULT_SORT;
            $order = $sort?->order ?? $order;
        }

        yield new EducationSort($field, $order);
    }
}
