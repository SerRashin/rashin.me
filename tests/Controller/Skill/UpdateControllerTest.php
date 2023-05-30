<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Entity\Section;
use RashinMe\Entity\Skill;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/skills/%s';

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
            'message' => 'You\'re not allowed to modify skills',
        ]);
    }

    public function testSkillNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Skill not found',
        ]);
    }

    public function testValidationErrors(): void
    {
        $skill = $this->createSkill();

        $this->login('test@user.com');
        $this->sendRequest('PATCH', sprintf(self::API_URL, $skill->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'name' => 'This value should not be blank.',
                'sectionId' => 'This value should not be blank or zero.',
                'imageId' => 'This value should not be blank or zero.',
            ]
        ]);
    }

    public function testImageNotFoundError(): void
    {
        $skill = $this->createSkill();
        $newSection = $this->createSection('new section name');

        $requestData = [
            'name' => 'Job name',
            'sectionId' => $newSection->getId(),
            'imageId' => 1123,
            'description' => 'description description description',
        ];

        $this->login('test@user.com');
        $this->sendRequest('PATCH', sprintf(self::API_URL, $skill->getId()), $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Image not found',
            'details' => [],
        ]);
    }

    public function testSectionNotFoundError(): void
    {
        $skill = $this->createSkill();
        $newFile = $this->createStorageFile();

        $requestData = [
            'name' => 'Job name',
            'sectionId' => 123,
            'imageId' => $newFile->getId(),
            'description' => 'description description description',
        ];

        $this->login('test@user.com');
        $this->sendRequest('PATCH', sprintf(self::API_URL, $skill->getId()), $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Section not found',
            'details' => [],
        ]);
    }

    public function testUpdateSkill(): void
    {
        $skill = $this->createSkill();
        $newSection = $this->createSection('new section name');
        $newFile = $this->createStorageFile();

        $requestData = [
            'name' => 'Job name',
            'sectionId' => $newSection->getId(),
            'imageId' => $newFile->getId(),
            'description' => 'description description description',
        ];

        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, $skill->getId()), $requestData);

        unset($requestData['imageId']);
        $requestData['image'] = [
            'id' => $newFile->getId(),
            'src' => $newFile->getPath() . $newFile->getName(),
        ];

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
    }

    private function createSkill(): Skill
    {
        $skill = new Skill(
            'name',
            $this->createSection('section'),
            $this->createStorageFile(),
            'description'
        );

        $this->saveEntities($skill);

        return $skill;
    }

    private function createSection(string $name): Section
    {
        $section = new Section(
            $name,
        );

        $this->saveEntities($section);

        return $section;
    }
}
