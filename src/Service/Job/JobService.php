<?php

declare(strict_types=1);

namespace RashinMe\Service\Job;

use DateTime;
use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Company;
use RashinMe\Entity\Job;
use RashinMe\Service\Job\Dto\DateData;
use RashinMe\Service\Job\Dto\JobData;
use RashinMe\Service\Job\Filter\JobFilter;
use RashinMe\Service\Job\Filter\JobSort;
use RashinMe\Service\Job\Repository\JobRepositoryInterface;

class JobService
{
    public function __construct(
        private readonly JobRepositoryInterface $jobRepository,
    ) {
    }

    /**
     * Add Job
     *
     * @param JobData $jobData
     *
     * @return Job
     */
    public function addJob(JobData $jobData): Job
    {
        $company = $jobData->company;

        $toDate = null;

        if ($jobData->to !== null) {
            $toDate = $this->getDateFromDateData($jobData->to);
        }

        $job = new Job(
            $jobData->name,
            $jobData->type,
            $jobData->description,
            new Company($company->name, $company->url),
            $this->getDateFromDateData($jobData->from),
            $toDate,
        );

        $this->jobRepository->save($job);

        return $job;
    }

    /**
     * Update job
     *
     * @param Job $job
     * @param JobData $jobData
     *
     * @return Job
     */
    public function updateJob(Job $job, JobData $jobData): Job
    {
        $toDate = null;

        if ($jobData->to !== null) {
            $toDate = $this->getDateFromDateData($jobData->to);
        }

        $company = $jobData->company;

        $job->changeName($jobData->name);
        $job->changeType($jobData->type);
        $job->changeDescription($jobData->description);
        $job->changeCompany(new Company($company->name, $company->url));
        $job->changeFromDate($this->getDateFromDateData($jobData->from));
        $job->changeToDate($toDate);

        $this->jobRepository->save($job);

        return $job;
    }

    /**
     * Get Job by id
     *
     * @param int $id
     *
     * @return Job|null
     */
    public function getJobById(int $id): ?Job
    {
        return $this->jobRepository->findOneById($id);
    }

    /**
     * Delete job
     *
     * @param Job $job
     *
     * @return void
     */
    public function deleteJob(Job $job): void
    {
        $this->jobRepository->delete($job);
    }

    /**
     * Get list of jobs
     *
     * @param JobFilter $filter
     * @param JobSort $sort
     *
     * @return Collection<int, Job>
     */
    public function getJobs(JobFilter $filter, JobSort $sort): Collection
    {
        return $this->jobRepository->getJobs($filter, $sort);
    }

    /**
     * Get count of jobs
     *
     * @param JobFilter $filter
     *
     * @return int
     */
    public function getCount(JobFilter $filter): int
    {
        return $this->jobRepository->getCount($filter);
    }

    /**
     * @param DateData $dateData
     *
     * @return DateTime
     */
    private function getDateFromDateData(DateData $dateData): DateTime
    {
        return new DateTime("$dateData->year-$dateData->month");
    }
}
