<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Entity\Section;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/skills';

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
            'message' => 'You\'re not allowed to create skills',
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
                'sectionId' => 'This value should not be blank.',
                'imageId' => 'This value should not be blank.',
            ]
        ]);
    }

    public function testImageValidationError(): void
    {
        $requestData = [
            'name' => 'Job name',
            'sectionId' => 0,
            'imageId' => 1,
            'description' => 'description description description',
        ];

        $this->login('test@user.com');
        $this->sendRequest('POST', self::API_URL, $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'image' => 'Image id not found'
            ]
        ]);
    }

    public function testSectionValidationError(): void
    {
        $file = $this->createStorageFile();

        $requestData = [
            'name' => 'Job name',
            'sectionId' => 1,
            'imageId' => $file->getId(),
            'description' => 'description description description',
        ];

        $this->login('test@user.com');
        $this->sendRequest('POST', self::API_URL, $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'section' => 'Section id not found'
            ]
        ]);
    }

    public function testCreateSkill(): void
    {
        $file = $this->createStorageFile();
        $section = $this->createSection('section name');

        $this->login('test@user.com');

        $requestData = [
            'name' => 'Job name',
            'sectionId' => $section->getId(),
            'imageId' => $file->getId(),
            'description' => 'description description description',
        ];

        $this->sendRequest('POST', self::API_URL, $requestData);

        unset($requestData['imageId']);
        $requestData['image'] = [
            'id' => $file->getId(),
            'src' => $file->getPath() . $file->getName(),
        ];
        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
    }

    /**
     * @param string $name
     *
     * @return Section
     */
    private function createSection(string $name): Section
    {
        $section = new Section(
            $name,
        );

        $this->saveEntities($section);

        return $section;
    }
}
