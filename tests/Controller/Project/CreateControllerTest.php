<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/projects';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerAdmin('test@user.com');
    }

    public function testAccessForbidden(): void
    {
        $this->sendRequest('POST', self::API_URL, []);

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => 403,
            'message' => 'You\'re not allowed to create projects',
        ]);
    }

    public function testValidationErrors(): void
    {
        $this->login('test@user.com');
        $this->sendRequest('POST', self::API_URL, []);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'name' => 'This value should not be blank.',
                'description' => 'This value should not be blank.',
                'imageId' => 'This value should not be blank.',
            ]
        ]);
    }

    public function testImageValidationError(): void
    {
        $requestData = [
            'name' => 'some name',
            'imageId' => 123,
            'description' => "Lorem ipsum dolar. Lorem ipsum dolar.",
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
        $this->sendRequest('POST', self::API_URL, $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'imageId' => 'Image id not found',
            ]
        ]);
    }

    public function testCreateEducation(): void
    {
        $file = $this->createStorageFile();

        $requestData = [
            'name' => 'some name',
            'imageId' => $file->getId(),
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

        $this->sendRequest('POST', self::API_URL, $requestData);

        unset($requestData['imageId']);
        $requestData['image'] = [
          'id' => $file->getId(),
          'src' => $file->getPath() . $file->getName(),
        ];

        $this->assertJsonContainsData($requestData);
    }
}
