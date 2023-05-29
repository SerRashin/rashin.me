<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Entity\Section;
use RashinMe\Entity\Skill;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class ViewControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/skills/%s';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSkillNotFound(): void
    {
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Skill not found',
        ]);
    }

    public function testSkillView(): void
    {
        $skill = $this->createSkill();

        $this->sendRequest('GET', sprintf(self::API_URL, $skill->getId()));

        $this->assertStatusCodeEqualsTo(200);
        $this->assertJsonEqualsData([
            'id' => $skill->getId(),
            'name' => $skill->getName(),
            'sectionId' => $skill->getSection()->getId(),
            'image' => [
                'id' => $skill->getImage()->getId(),
                'src' => $skill->getImage()->getPath() . $skill->getImage()->getName(),
            ],
            'description' => $skill->getDescription(),
        ]);
    }

    private function createSkill(): Skill
    {
        $skill = new Skill(
            'name',
            $this->createSection(),
            $this->createStorageFile(),
            'description'
        );

        $this->saveEntities($skill);

        return $skill;
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
