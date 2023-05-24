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
use RashinMe\Entity\Education;
use RashinMe\Service\Education\Dto\EducationFilter;
use RashinMe\Service\Education\Dto\EducationSort;
use RashinMe\Service\Education\Repository\EducationRepositoryInterface;

/**
 * Education repository
 */
class EducationRepository implements EducationRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(Education $education): void
    {
        $this->entityManager->persist($education);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(Education $education): void
    {
        $this->entityManager->remove($education);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function findOneById(int $id): ?Education
    {
        return $this->getRepository()->findOneBy(["id" => $id]);
    }

    /**
     * @inheritDoc
     */
    public function getEducations(EducationFilter $filter, EducationSort $sort): Collection
    {
        $query = $this->createQueryBuilder()
            ->addSelect('education')
            ->from(Education::class, 'education');

        if ($filter->limit !== 0) {
            $query->setMaxResults($filter->limit);
        }

        if ($filter->offset !== 0) {
            $query->setFirstResult($filter->offset);
        }

        $field = $this->getSortField($sort->field);

        $query->addOrderBy($field, $sort->order);

        /** @var array<int, Education> $jobs */
        $jobs = $query
            ->getQuery()
            ->getResult();

        return new ArrayCollection($jobs);
    }

    /**
     * @inheritDoc
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getCount(EducationFilter $filter): int
    {
        $query = $this->createQueryBuilder()
            ->addSelect('COUNT(education)')
            ->from(Education::class, 'education');

        // @phpstan-ignore-next-line
        return (int) $query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return ObjectRepository<Education>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Education::class);
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
            'id' => 'education.id',
            'name' => 'education.name',
            'from' => 'education.fromDate',
            default => 'id',
        };
    }
}
