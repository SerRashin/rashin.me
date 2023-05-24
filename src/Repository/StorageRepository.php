<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use RashinMe\Entity\File;
use RashinMe\Entity\Job;
use RashinMe\Service\Storage\Repository\StorageRepositoryInterface;

class StorageRepository implements StorageRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(File $file): void
    {
        $this->entityManager->persist($file);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function findOneById(int $id): ?File
    {
        return $this->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * @return ObjectRepository<File>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(File::class);
    }
}
