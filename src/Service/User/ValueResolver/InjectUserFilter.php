<?php

declare(strict_types=1);

namespace RashinMe\Service\User\ValueResolver;

use RashinMe\Service\User\Filter\UserFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectUserFilter implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<UserFilter>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== UserFilter::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $limit = $request->query->getInt('limit', UserFilter::USERS_PER_PAGE);
        $offset = $request->query->getInt('offset', 0);

        yield new UserFilter($limit, $offset);
    }
}
