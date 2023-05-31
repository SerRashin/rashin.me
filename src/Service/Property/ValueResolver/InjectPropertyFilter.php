<?php

declare(strict_types=1);

namespace RashinMe\Service\Property\ValueResolver;

use RashinMe\Service\Property\Filter\PropertyFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectPropertyFilter implements ValueResolverInterface
{
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return iterable<PropertyFilter>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType) {
            return [];
        }

        if ($argumentType !== PropertyFilter::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $fields = $request->query->get('filter', null);
        $filter = null;
        if ($fields !== null) {
            /**
             * @var object $filter
             */
            $filter = json_decode((string)$fields, false);
        }

        $propertyFilter = new PropertyFilter();

        if ($filter !== null && property_exists($filter, 'fields')) {
            $propertyFilter->setFields($filter->fields);
        }

        yield $propertyFilter;
    }
}
