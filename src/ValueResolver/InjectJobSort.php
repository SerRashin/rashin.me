<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Job\Filter\JobSort;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectJobSort implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<JobSort>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== JobSort::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $sortJson = $request->query->get('sort') ?? '';

        $field = JobSort::DEFAULT_SORT;
        $order = JobSort::DEFAULT_ORDER;

        if (!empty($sortJson)) {
            /**
             * @var object{field: string, order: string}|null $sort
             */
            $sort = json_decode((string)$sortJson, false);

            $field = $sort?->field ?? JobSort::DEFAULT_SORT;
            $order = $sort?->order ?? $order;
        }

        yield new JobSort($field, $order);
    }
}
