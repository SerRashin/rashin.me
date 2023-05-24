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
use RashinMe\Entity\Job;
use RashinMe\Service\Job\Dto\JobFilter;
use RashinMe\Service\Job\Dto\JobSort;
use RashinMe\Service\Job\Repository\JobRepositoryInterface;

/**
 * Job repository
 */
class JobRepository implements JobRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(Job $job): void
    {
        $this->entityManager->persist($job);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(Job $job): void
    {
        $this->entityManager->remove($job);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function findOneById(int $id): ?Job
    {
        return $this->getRepository()->findOneBy(["id" => $id]);
    }

    /**
     * @inheritDoc
     */
    public function getJobs(JobFilter $filter, JobSort $sort): Collection
    {
        $query = $this->createQueryBuilder()
            ->addSelect('job')
            ->from(Job::class, 'job');

        if ($filter->limit !== null) {
            $query->setMaxResults($filter->limit);
        }

        if ($filter->offset !== 0) {
            $query->setFirstResult($filter->offset);
        }

        $field = $this->getSortField($sort->field);

        $query->addOrderBy($field, $sort->order);

        /** @var array<int, Job> $jobs */
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
    public function getCount(JobFilter $filter): int
    {
        $query = $this->createQueryBuilder()
            ->addSelect('COUNT(j)')
            ->from(Job::class, 'j');

        // @phpstan-ignore-next-line
        return (int) $query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return ObjectRepository<Job>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Job::class);
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
            'id' => 'job.id',
            'name' => 'job.name',
            'type' => 'job.type',
            'from' => 'job.fromDate',
            'company.name' => 'job.company.name',
            default => 'id',
        };
    }
}
