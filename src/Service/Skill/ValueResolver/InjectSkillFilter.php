<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\ValueResolver;

use RashinMe\Service\Skill\Dto\SkillFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectSkillFilter implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<SkillFilter>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== SkillFilter::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $limit = $request->query->getInt('limit', SkillFilter::SKILLS_PER_PAGE);
        $offset = $request->query->getInt('offset', 0);

        yield new SkillFilter($limit, $offset);
    }
}
