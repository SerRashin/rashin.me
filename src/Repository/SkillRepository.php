<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use RashinMe\Entity\Skill;
use RashinMe\Service\Skill\Filter\SkillFilter;
use RashinMe\Service\Skill\Filter\SkillSort;
use RashinMe\Service\Skill\Repository\SkillRepositoryInterface;

class SkillRepository implements SkillRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(Skill $skill): void
    {
        $this->entityManager->persist($skill);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(Skill $skill): void
    {
        $this->entityManager->remove($skill);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function findOneById(int $id): ?Skill
    {
        $query = $this->createQueryBuilder()
            ->addSelect('skill', 'section', 'image')
            ->from(Skill::class, 'skill');

        $query->leftJoin('skill.section', 'section');
        $query->leftJoin('skill.image', 'image');
        $query->where('skill.id = :skill');
        $query->setParameter(':skill', $id);

        // @phpstan-ignore-next-line
        return $query->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function getSkills(SkillFilter $filter, SkillSort $sort): Collection
    {
        $query = $this->createQueryBuilder()
            ->addSelect('skill')
            ->from(Skill::class, 'skill');

        $query->leftJoin('skill.image', 'image')
            ->addSelect('image');

        if ($filter->limit !== 0) {
            $query->setMaxResults($filter->limit);
        }

        if ($filter->offset !== 0) {
            $query->setFirstResult($filter->offset);
        }

        $field = $this->getSortField($sort->field);

        /** @var array<int, Skill> $skills */
        $skills = $query
            ->addOrderBy($field, $sort->order)
            ->getQuery()
            ->getResult();

        return new ArrayCollection($skills);
    }

    /**
     * @inheritDoc
     */
    public function getCount(SkillFilter $filter): int
    {
        $query = $this->createQueryBuilder()
            ->addSelect('COUNT(s)')
            ->from(Skill::class, 's');

        // @phpstan-ignore-next-line
        return (int) $query->getQuery()
            ->getSingleScalarResult();
    }

    private function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    public function getSortField(string $fieldName): string
    {
        return match ($fieldName) {
            'name' => 'skill.name',
            default => 'skill.id',
        };
    }
}
