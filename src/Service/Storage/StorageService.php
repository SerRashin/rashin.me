<?php

declare(strict_types=1);

namespace RashinMe\Service\Storage;

use RashinMe\Entity\File;
use RashinMe\Service\Storage\Repository\StorageRepositoryInterface;

class StorageService
{
    public function __construct(
        private readonly StorageRepositoryInterface $storageRepository,
    ) {
    }

    /**
     * Get file by id
     *
     * @param int $id
     *
     * @return File|null
     */
    public function getFileById(int $id): ?File
    {
        return $this->storageRepository->findOneById($id);
    }
}
