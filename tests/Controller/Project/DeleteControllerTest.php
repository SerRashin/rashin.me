<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Entity\Project;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/projects/%s';

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
            'message' => 'You\'re not allowed to delete projects',
        ]);
    }

    public function testProjectNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Project not found',
        ]);
    }

    public function testDeleteProject(): void
    {
        $project = $this->createProject();

        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, $project->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
    }

    private function createProject(): Project
    {
        $file = $this->createStorageFile();

        $project = new Project(
            'name',
            'description',
            $file,
        );

        $this->saveEntities($project);

        return $project;
    }
}
