<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use RashinMe\Entity\Project;
use RashinMe\Service\Project\Filter\ProjectFilter;
use RashinMe\Service\Project\Filter\ProjectSort;
use RashinMe\Service\Project\Repository\ProjectRepositoryInterface;

/**
 * Project repository
 */
class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(Project $project): void
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(Project $project): void
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Project
    {
        $query = $this->createQueryBuilder()
            ->addSelect('p', 't', 'l')
            ->from(Project::class, 'p');

        $query->leftJoin('p.tags', 't');
        $query->leftJoin('p.links', 'l');
        $query->where('p.id = :project');
        $query->setParameter(':project', $id);

        // @phpstan-ignore-next-line
        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function getProjects(ProjectFilter $filter, ProjectSort $sort): Collection
    {
        $query = $this->createQueryBuilder()
            ->select('project')
            ->from(Project::class, 'project');

        $query->leftJoin('project.tags', 'tags')
            ->addSelect('tags');

        $query->leftJoin('project.links', 'links')
            ->addSelect('links');

        $query->leftJoin('project.image', 'image')
            ->addSelect('image');

        $ids = $this->getProjectIds($filter, $sort);

        $field = $this->getSortField($sort->field);

        /** @var array<int, Project> $projects */
        $projects = $query
            ->where('project.id IN(:projectIds)')
            ->setParameter('projectIds', $ids)
            ->orderBy($field, $sort->order)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getResult();

        return new ArrayCollection($projects);
    }

    /**
     * @param ProjectFilter $filter
     *
     * @return int[]
     */
    private function getProjectIds(ProjectFilter $filter, ProjectSort $sort): array
    {
        $query = $this->createQueryBuilder()
            ->addSelect('project.id, project.name')
            ->from(Project::class, 'project');

        if ($filter->limit !== 0) {
            $query->setMaxResults($filter->limit);
        }

        if ($filter->offset !== 0) {
            $query->setFirstResult($filter->offset);
        }

        $field = $this->getSortField($sort->field);

        /** @var array<int> $ids */
        $ids = $query
            ->addOrderBy($field, $sort->order)
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
    public function getCount(ProjectFilter $filter): int
    {
        $query = $this->createQueryBuilder()
            ->addSelect('COUNT(p)')
            ->from(Project::class, 'p');

        // @phpstan-ignore-next-line
        return (int) $query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return QueryBuilder
     */
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
            'name' => 'project.name',
            default => 'project.id',
        };
    }
}
