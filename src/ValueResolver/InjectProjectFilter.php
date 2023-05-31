<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Project\Filter\ProjectFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectProjectFilter implements ValueResolverInterface
{
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return iterable<ProjectFilter>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType) {
            return [];
        }

        if ($argumentType !== ProjectFilter::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

//        $filter = $request->query->filter('filter');

        $limit = $request->query->getInt('limit', ProjectFilter::PROJECTS_PER_PAGE);
        $offset = $request->query->getInt('offset', 0);

        yield new ProjectFilter($limit, $offset);
    }
}
