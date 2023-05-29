<?php

declare(strict_types=1);

namespace RashinMe\Service\Job;

use DateTime;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RashinMe\Entity\Company;
use RashinMe\Entity\Job;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Job\Dto\CompanyData;
use RashinMe\Service\Job\Dto\DateData;
use RashinMe\Service\Job\Dto\JobData;
use RashinMe\Service\Job\Dto\JobFilter;
use RashinMe\Service\Job\Dto\JobSort;
use RashinMe\Service\Job\Repository\JobRepositoryInterface;
use RashinMe\Service\Validation\ValidationError;
use RashinMe\Service\Validation\ValidationServiceInterface;

class JobServiceTest extends TestCase
{
    /**
     * @var JobRepositoryInterface&MockObject
     */
    private JobRepositoryInterface $jobRepository;

    /**
     * @var ValidationServiceInterface&MockObject
     */
    private ValidationServiceInterface $validationService;

    private JobService $service;

    private JobData $testData;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->jobRepository = $this->createMock(JobRepositoryInterface::class);
        $this->validationService = $this->createMock(ValidationServiceInterface::class);

        $this->service = new JobService(
            $this->jobRepository,
            $this->validationService,
        );

        $this->testData = new JobData(
            'name',
            new CompanyData(
                'company.name',
                'company.url',
            ),
            'description',
            'type of work',
            new DateData(1, 2010),
        );
    }

    public function testAddingJobWithInvalidData(): void
    {
        $expectedError = new ValidationError("some error", []);

        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn($expectedError);

        $result = $this->service->addJob($this->testData);

        $this->assertInstanceOf(ErrorInterface::class, $result);
        $this->assertSame($expectedError, $result);
    }

    public function testJobSaving(): void
    {
        $this->testData = new JobData(
            'name',
            new CompanyData(
                'company.name',
                'company.url',
            ),
            'description',
            'type of work',
            new DateData(1, 2010),
            new DateData(1, 2011),
        );

        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn(null);

        $expectedJob = null;
        $this->jobRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Job $job) use (&$expectedJob): bool {
                $this->assertEquals($this->testData->name, $job->getName());
                $this->assertEquals($this->testData->type, $job->getType());
                $this->assertEquals($this->testData->description, $job->getDescription());
                $this->assertEquals($this->testData->company->name, $job->getCompany()->getName());
                $this->assertEquals($this->testData->company->url, $job->getCompany()->getUrl());
                $this->assertEquals($this->testData->from->month, $job->getFromDate()->format('m'));
                $this->assertEquals($this->testData->from->year, $job->getFromDate()->format('Y'));

                $expectedJob = $job;

                return true;
            }));

        $result = $this->service->addJob($this->testData);

        $this->assertInstanceOf(Job::class, $result);
        $this->assertsame($expectedJob, $result);
    }

    public function testUpdatingJobWithInvalidData(): void
    {
        $jobEntity = new Job(
            'job name',
            'job type',
            'job description',
            new Company('job company name', 'job company url'),
            new DateTime('now'),
            new DateTime('now'),
        );

        $expectedError = new ValidationError("some error", []);

        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn($expectedError);

        $result = $this->service->updateJob($jobEntity, $this->testData);

        $this->assertInstanceOf(ErrorInterface::class, $result);
        $this->assertSame($expectedError, $result);
    }

    public function testJobUpdating(): void
    {
        $jobEntity = new Job(
            'job name',
            'job type',
            'job description',
            new Company('job company name', 'job company url'),
            new DateTime('now'),
            new DateTime('now'),
        );

        $this->testData = new JobData(
            'name',
            new CompanyData(
                'company.name',
                'company.url',
            ),
            'description',
            'type of work',
            new DateData(1, 2010),
            new DateData(1, 2011),
        );

        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn(null);

        $this->jobRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Job $job): bool {
                $this->assertEquals($this->testData->name, $job->getName());
                $this->assertEquals($this->testData->type, $job->getType());
                $this->assertEquals($this->testData->description, $job->getDescription());
                $this->assertEquals($this->testData->company->name, $job->getCompany()->getName());
                $this->assertEquals($this->testData->company->url, $job->getCompany()->getUrl());
                $this->assertEquals($this->testData->from->month, $job->getFromDate()->format('m'));
                $this->assertEquals($this->testData->from->year, $job->getFromDate()->format('Y'));

                return true;
            }));

        $result = $this->service->updateJob($jobEntity, $this->testData);

        $this->assertInstanceOf(Job::class, $result);
        $this->assertsame($jobEntity, $result);
    }

    public function testGetNotExistsJobById(): void
    {
        $jobId = 123;

        $this->jobRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->identicalTo($jobId))
            ->willReturn(null);

        $job = $this->service->getJobById($jobId);

        $this->assertNull($job);
    }
    public function testGetJobById(): void
    {
        $jobId = 123;

        $expectedJob = $this->createMock(Job::class);
        $this->jobRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->identicalTo($jobId))
            ->willReturn($expectedJob);

        $job = $this->service->getJobById($jobId);

        $this->assertEquals($expectedJob, $job);
    }

    public function testDeleteJob(): void
    {
        $jobEntity = new Job(
            'job name',
            'job type',
            'job description',
            new Company('job company name', 'job company url'),
            new DateTime('now'),
            new DateTime('now'),
        );

        $this->jobRepository
            ->expects($this->once())
            ->method('delete')
            ->with($this->callback(function (Job $job) use (&$jobEntity): bool {
                $this->assertEquals($jobEntity, $job);

                return true;
            }));

        $this->service->deleteJob($jobEntity);
    }

    public function testGetJobsCount(): void
    {
        $expectedCount = 123;

        $filter = new JobFilter(10, 0);

        $this->jobRepository
            ->expects($this->once())
            ->method('getCount')
            ->with($this->identicalTo($filter))
            ->willReturn($expectedCount);

        $result = $this->service->getCount($filter);

        $this->assertEquals($expectedCount, $result);
    }

    public function testGetJobs(): void
    {
        $expectedCollection = $this->createMock(Collection::class);

        $filter = new JobFilter(10, 0);
        $sort = new JobSort('id', 'asc');

        $this->jobRepository
            ->expects($this->once())
            ->method('getJobs')
            ->with($this->identicalTo($filter))
            ->willReturn($expectedCollection);

        $result = $this->service->getJobs($filter, $sort);

        $this->assertEquals($expectedCollection, $result);
    }
}
