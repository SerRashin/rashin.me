<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use DateTime;
use RashinMe\Entity\Company;
use RashinMe\Entity\Job;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class ViewControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/jobs/%s';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testJobNotFound(): void
    {
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Job not found',
        ]);
    }

    public function testJobView(): void
    {
        $job = $this->createJob();

        $this->sendRequest('GET', sprintf(self::API_URL, $job->getId()));

        $this->assertStatusCodeEqualsTo(200);
        $this->assertJsonEqualsData([
            'id' => $job->getId(),
            'name' => $job->getName(),
            'company' => [
                'name' => $job->getCompany()->getName(),
                'url' => $job->getCompany()->getUrl(),
            ],
            'description' => $job->getDescription(),
            'type' => $job->getType(),
            'from' => [
                'month' => $job->getFromDate()->format('m'),
                'year' => $job->getFromDate()->format('Y'),
            ],
            'to' => [
                'month' => $job->getToDate()?->format('m'),
                'year' => $job->getToDate()?->format('Y'),
            ],
        ]);
    }

    private function createJob(): Job
    {
        $job = new Job(
            'name',
            'type',
            'description',
            new Company(
                'company.name',
                'company.url',
            ),
            new DateTime('now'),
            new DateTime('now'),
        );

        $this->saveEntities($job);

        return $job;
    }
}
