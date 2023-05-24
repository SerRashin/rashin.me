<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\Section;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Skill\Repository\SectionRepositoryInterface;

class SectionRepositoryTest extends FunctionalTestCase
{
    private SectionRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new SectionRepository($this->getEntityManager());
    }

    public function testSaveSection(): void
    {
        $section = new Section(
            'section name'
        );

        $this->repository->save($section);

        $savedSection = $this->repository->findOneById($section->getId());

        $this->assertEquals($section, $savedSection);
    }
}
