<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Project\Filter\ProjectSort;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectProjectSort implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<ProjectSort>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== ProjectSort::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $sortJson = $request->query->get('sort') ?? '';

        $field = ProjectSort::DEFAULT_SORT;
        $order = ProjectSort::DEFAULT_ORDER;

        if (!empty($sortJson)) {
            /**
             * @var object{field: string, order: string}|null $sort
             */
            $sort = json_decode((string)$sortJson, false);

            $field = $sort?->field ?? ProjectSort::DEFAULT_SORT;
            $order = $sort?->order ?? $order;
        }

        yield new ProjectSort($field, $order);
    }
}
