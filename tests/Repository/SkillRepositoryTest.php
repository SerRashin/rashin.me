<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\File;
use RashinMe\Entity\Section;
use RashinMe\Entity\Skill;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Skill\Repository\SkillRepositoryInterface;

class SkillRepositoryTest extends FunctionalTestCase
{
    private SkillRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new SkillRepository($this->getEntityManager());
    }

    public function testSaveSkill(): void
    {
        $em = $this->getEntityManager();

        $file = new File(
            'file.ext',
            '/path/to/file/',
            'mime/type',
            100,
        );
        $section = new Section('section name');

        $em->persist($file);
        $em->persist($section);

        $skill = new Skill(
            'skill name',
            $section,
            $file,
            'description',
        );

        $this->repository->save($skill);

        $savedSkill = $this->repository->findOneById($skill->getId());

        $this->assertEquals($skill, $savedSkill);
    }
}
