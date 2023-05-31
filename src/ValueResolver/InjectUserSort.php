<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\User\Filter\UserSort;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectUserSort implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<UserSort>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== UserSort::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $sortJson = $request->query->get('sort') ?? '';

        $field = UserSort::DEFAULT_SORT;
        $order = UserSort::DEFAULT_ORDER;

        if (!empty($sortJson)) {
            /**
             * @var object{field: string, order: string}|null $sort
             */
            $sort = json_decode((string)$sortJson, false);

            $field = $sort?->field ?? UserSort::DEFAULT_SORT;
            $order = $sort?->order ?? $order;
        }

        yield new UserSort($field, $order);
    }
}
