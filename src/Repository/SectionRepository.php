<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use RashinMe\Entity\Section;
use RashinMe\Service\Skill\Dto\SectionFilter;
use RashinMe\Service\Skill\Repository\SectionRepositoryInterface;

class SectionRepository implements SectionRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(Section $section): void
    {
        $this->entityManager->persist($section);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(Section $section): void
    {
        $this->entityManager->remove($section);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function findOneById(int $id): ?Section
    {
        return $this->getRepository()->findOneBy(["id" => $id]);
    }

    /**
     * @inheritDoc
     */
    public function getSections(SectionFilter $filter): Collection
    {
        $query = $this->createQueryBuilder()
            ->addSelect('section')
            ->from(Section::class, 'section');

        $query->leftJoin('section.skills', 'skills')
            ->addSelect('skills');

        $ids = $this->getSectionIds($filter);

        /** @var array<int, Section> $skills */
        $skills = $query
            ->addOrderBy('section.id', 'ASC')
            ->where('section.id IN(:sectionIds)')
            ->setParameter('sectionIds', array_values($ids))
            ->getQuery()
            ->getResult();

        return new ArrayCollection($skills);
    }

    /**
     * @param SectionFilter $filter
     *
     * @return int[]
     */
    private function getSectionIds(SectionFilter $filter): array
    {
        $query = $this->createQueryBuilder()
            ->addSelect('DISTINCT s.id')
            ->from(Section::class, 's');

        if ($filter->limit !== 0) {
            $query->setMaxResults($filter->limit);
        }

        if ($filter->offset !== 0) {
            $query->setFirstResult($filter->offset);
        }

        /** @var array<int> $ids */
        $ids = $query
            ->addOrderBy('s.id', 'ASC')
            ->getQuery()
            ->getArrayResult();

        return $ids;
    }

    /**
     * @inheritDoc
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getCount(SectionFilter $filter): int
    {
        $query = $this->createQueryBuilder()
            ->addSelect('COUNT(s)')
            ->from(Section::class, 's');

        // @phpstan-ignore-next-line
        return (int) $query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return ObjectRepository<Section>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Section::class);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }
}
