<?php

declare(strict_types=1);

namespace RashinMe\Service\Project\Repository;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Project;
use RashinMe\Service\Project\Filter\ProjectFilter;
use RashinMe\Service\Project\Filter\ProjectSort;

/**
 * Project repository interface
 */
interface ProjectRepositoryInterface
{
    /**
     * Save Job
     *
     * @param Project $project
     *
     * @return void
     */
    public function save(Project $project): void;

    /**
     * Delete Project
     *
     * @param Project $project
     *
     * @return void
     */
    public function delete(Project $project): void;

    /**
     * Find Project by id
     *
     * @param int $id
     *
     * @return Project|null
     */
    public function findOneById(int $id): ?Project;

    /**
     * Get projects
     *
     * @param ProjectFilter $filter
     * @param ProjectSort $sort
     *
     * @return Collection<int, Project>
     */
    public function getProjects(ProjectFilter $filter, ProjectSort $sort): Collection;

    /**
     * Get count of projects
     *
     * @param ProjectFilter $filter
     *
     * @return int
     */
    public function getCount(ProjectFilter $filter): int;
}
