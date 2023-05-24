<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use DateTime;
use RashinMe\Entity\Education;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Education\Repository\EducationRepositoryInterface;

class EducationRepositoryTest extends FunctionalTestCase
{
    private EducationRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EducationRepository($this->getEntityManager());
    }

    public function testSaveEducation(): void
    {
        $education = new Education(
            'institution',
            'faculty',
            'specialization',
            new DateTime('20-01-2020'),
            new DateTime('20-03-2020'),
        );

        $this->repository->save($education);

        $savedEducation = $this->repository->findOneById($education->getId());

        $this->assertEquals($education, $savedEducation);
    }
}
