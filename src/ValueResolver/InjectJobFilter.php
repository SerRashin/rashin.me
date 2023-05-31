<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Job\Filter\JobFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectJobFilter implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<JobFilter>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== JobFilter::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $limit = $request->query->getInt('limit', JobFilter::JOBS_PER_PAGE);
        $offset = $request->query->getInt('offset', 0);

        yield new JobFilter($limit, $offset);
    }
}
