<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use DateTime;
use RashinMe\Entity\Company;
use RashinMe\Entity\Job;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Job\Repository\JobRepositoryInterface;

class JobRepositoryTest extends FunctionalTestCase
{
    private JobRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new JobRepository($this->getEntityManager());
    }

    public function testSaveJob(): void
    {
        $job = new Job(
            'job name',
            'type',
            'some description',
            new Company('name', 'url-to-company'),
            new DateTime('01-01-2001'),
            new DateTime('01-01-2002'),
        );

        $this->repository->save($job);

        $savedJob = $this->repository->findOneById($job->getId());

        $this->assertEquals($job, $savedJob);
    }
}
