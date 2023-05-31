<?php

declare(strict_types=1);

namespace RashinMe\ValueResolver;

use RashinMe\Service\Skill\Filter\SkillSort;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class InjectSkillSort implements ValueResolverInterface
{
    /**
     * @inheritDoc
     *
     * @return iterable<SkillSort>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== SkillSort::class) {
            return [];
        }

        if (!class_exists($argumentType)) {
            return [];
        }

        $sortJson = $request->query->get('sort') ?? '';

        $field = SkillSort::DEFAULT_SORT;
        $order = SkillSort::DEFAULT_ORDER;

        if (!empty($sortJson)) {
            /**
             * @var object{field: string, order: string}|null $sort
             */
            $sort = json_decode((string)$sortJson, false);

            $field = $sort?->field ?? SkillSort::DEFAULT_SORT;
            $order = $sort?->order ?? $order;
        }

        yield new SkillSort($field, $order);
    }
}
