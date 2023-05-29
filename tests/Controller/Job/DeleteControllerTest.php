<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use DateTime;
use RashinMe\Entity\Company;
use RashinMe\Entity\Job;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/jobs/%s';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerAdmin('test@user.com');
    }

    public function testAccessForbidden(): void
    {
        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'You\'re not allowed to delete jobs',
        ]);
    }

    public function testJobNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Job not found',
        ]);
    }

    public function testDeleteJob(): void
    {
        $job = $this->createJob();

        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, $job->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
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
