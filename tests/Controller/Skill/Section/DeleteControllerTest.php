<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill\Section;

use RashinMe\Entity\Section;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/sections/%s';

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
            'message' => 'You\'re not allowed to delete sections',
        ]);
    }

    public function testSectionNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Section not found',
        ]);
    }

    public function testDeleteSection(): void
    {
        $section = $this->createSection();

        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, $section->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
    }

    private function createSection(): Section
    {
        $section = new Section(
            'name',
        );

        $this->saveEntities($section);

        return $section;
    }
}
