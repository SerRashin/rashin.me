<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\File;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Storage\Repository\StorageRepositoryInterface;

class StorageRepositoryTest extends FunctionalTestCase
{
    private StorageRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new StorageRepository($this->getEntityManager());
    }

    public function testSaveFile(): void
    {
        $file = new File(
            'file.ext',
            '/path/to/file/',
            'mime/type',
            100,
        );

        $this->repository->save($file);

        $savedFile = $this->repository->findOneById($file->getId());

        $this->assertEquals($file, $savedFile);
    }
}
