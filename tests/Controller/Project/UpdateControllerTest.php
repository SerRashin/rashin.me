<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Entity\Project;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/projects/%s';

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
            'message' => 'You\'re not allowed to modify projects',
        ]);
    }

    public function testProjectNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Project not found',
        ]);
    }

    public function testValidationErrors(): void
    {
        $project = $this->createProject();

        $this->login('test@user.com');
        $this->sendRequest('PATCH', sprintf(self::API_URL, $project->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'name' => 'This value should not be blank.',
                'description' => 'This value should not be blank.',
                'imageId' => 'This value should not be blank or zero.',
            ]
        ]);
    }

    public function testImageValidationError(): void
    {
        $project = $this->createProject();

        $requestData = [
            'name' => 'some name',
            'imageId' => 3213,
            'description' => 'Lorem ipsum dolar. Lorem ipsum dolar.',
            'tags' => [
                'Tag1',
                'Tag2',
            ],
            'links' => [
                [
                    'title' => "GitHub",
                    'url' => 'https://github.com/some/project'
                ]
            ]
        ];

        $this->login('test@user.com');
        $this->sendRequest('PATCH', sprintf(self::API_URL, $project->getId()), $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Image not found',
            'details' => []
        ]);
    }

    public function testUpdateProject(): void
    {
        $project = $this->createProject();
        $newFile = $this->createStorageFile();

        $requestData = [
            'name' => 'some name',
            'imageId' => $newFile->getId(),
            'description' => 'Lorem ipsum dolar. Lorem ipsum dolar.',
            'tags' => [
                'Tag1',
                'Tag2',
            ],
            'links' => [
                [
                    'title' => "GitHub",
                    'url' => 'https://github.com/some/project'
                ]
            ]
        ];

        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, $project->getId()), $requestData);

        unset($requestData['imageId']);
        $requestData['image'] = [
            'id' => $newFile->getId(),
            'src' => $newFile->getPath() . $newFile->getName(),
        ];

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
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
