<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\File;
use RashinMe\Entity\Project;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Project\Repository\ProjectRepositoryInterface;

class ProjectRepositoryTest extends FunctionalTestCase
{
    private ProjectRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ProjectRepository($this->getEntityManager());
    }

    public function testSaveProject(): void
    {
        $project = new Project(
            'job name',
            'type',
            new File(
                'somefile.jpeg',
                'path/to/file/',
                'image/png',
                300,
            ),
        );

        $this->repository->save($project);

        $savedProject = $this->repository->findOneById($project->getId());

        $this->assertEquals($project, $savedProject);
    }
}
