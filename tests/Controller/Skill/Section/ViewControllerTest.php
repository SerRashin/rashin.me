<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill\Section;

use RashinMe\Entity\Section;
use RashinMe\Entity\Skill;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class ViewControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/sections/%s';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSectionNotFound(): void
    {
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Section not found',
        ]);
    }

    public function testSectionView(): void
    {
        $section = $this->createSection();

        $this->sendRequest('GET', sprintf(self::API_URL, $section->getId()));

        $this->assertStatusCodeEqualsTo(200);
        $this->assertJsonEqualsData([
            'id' => $section->getId(),
            'name' => $section->getName(),
            'skills' => [],
        ]);
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
