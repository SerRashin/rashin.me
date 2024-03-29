<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Skill\Filter\SectionFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectSectionFilter implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<SectionFilter>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== SectionFilter::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $limit = $request->query->getInt('limit', SectionFilter::SECTIONS_PER_PAGE);
        $offset = $request->query->getInt('offset', 0);

        yield new SectionFilter($limit, $offset);
    }
}
