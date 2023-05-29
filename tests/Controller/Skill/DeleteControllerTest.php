<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Entity\Section;
use RashinMe\Entity\Skill;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/skills/%s';

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
            'message' => 'You\'re not allowed to delete skills',
        ]);
    }

    public function testSkillNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Skill not found',
        ]);
    }

    public function testDeleteSkill(): void
    {
        $skill = $this->createSkill();

        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, $skill->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
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
