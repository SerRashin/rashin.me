<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use DateTime;
use RashinMe\Entity\Company;
use RashinMe\Entity\Job;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/jobs/%s';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerAdmin('test@user.com');
    }

    public function testAccessForbidden(): void
    {
        $this->sendRequest('PATCH', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'You\'re not allowed to modify jobs',
        ]);
    }

    public function testJobNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Job not found',
        ]);
    }

    public function testUpdateJob(): void
    {
        $job = $this->createJob();

        $requestData = [
            'name' => 'changed job name',
            'company' => [
                'name' => 'changed some company',
                'url' => 'https://new.site.company.com'
            ],
            'description' => 'changed some long description of job',
            'type' => 'changed type-name',
            'from' => [
                'month' => 11,
                'year' => 2011,
            ]
        ];

        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, $job->getId()), $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
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
