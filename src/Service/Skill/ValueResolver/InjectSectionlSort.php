<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\ValueResolver;

use RashinMe\Service\Skill\Filter\SectionSort;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectSectionlSort implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<SectionSort>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== SectionSort::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $sortJson = $request->query->get('sort') ?? '';

        $field = SectionSort::DEFAULT_SORT;
        $order = SectionSort::DEFAULT_ORDER;

        if (!empty($sortJson)) {
            /**
             * @var object{field: string, order: string}|null $sort
             */
            $sort = json_decode((string)$sortJson, false);

            $field = $sort?->field ?? SectionSort::DEFAULT_SORT;
            $order = $sort?->order ?? $order;
        }

        yield new SectionSort($field, $order);
    }
}
