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

        if ($filter->limit !== null) {
            $query->setMaxResults($filter->limit);
        }

        if ($filter->offset !== 0) {
            $query->setFirstResult($filter->offset);
        }

        $query->addOrderBy('section.id', 'ASC');

        /** @var array<int, Section> $skills */
        $skills = $query
            ->getQuery()
            ->getResult();

        return new ArrayCollection($skills);
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
