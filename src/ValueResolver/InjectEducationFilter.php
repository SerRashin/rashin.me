<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Education\Filter\EducationFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectEducationFilter implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<EducationFilter>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== EducationFilter::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $limit = $request->query->getInt('limit', EducationFilter::EDUCATIONS_PER_PAGE);
        $offset = $request->query->getInt('offset', 0);

        yield new EducationFilter($limit, $offset);
    }
}
