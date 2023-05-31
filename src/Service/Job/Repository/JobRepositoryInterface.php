<?php

declare(strict_types=1);

namespace RashinMe\Service\Job\Repository;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Job;
use RashinMe\Service\Job\Filter\JobFilter;
use RashinMe\Service\Job\Filter\JobSort;

interface JobRepositoryInterface
{
    /**
     * Save Job
     *
     * @param Job $job
     *
     * @return void
     */
    public function save(Job $job): void;

    /**
     * Delete job
     *
     * @param Job $job
     *
     * @return void
     */
    public function delete(Job $job): void;

    /**
     * Find job by id
     *
     * @param int $id
     *
     * @return Job|null
     */
    public function findOneById(int $id): ?Job;

    /**
     * Get jobs collection
     *
     * @param JobFilter $filter
     * @param JobSort $sort
     *
     * @return Collection<int, Job>
     */
    public function getJobs(JobFilter $filter, JobSort $sort): Collection;

    /**
     * Get jobs count
     *
     * @param JobFilter $filter
     *
     * @return int
     */
    public function getCount(JobFilter $filter): int;
}
