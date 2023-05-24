<?php

declare(strict_types=1);

namespace RashinMe\Service\Storage\Repository;

use RashinMe\Entity\File;

interface StorageRepositoryInterface
{
    /**
     * Save file
     *
     * @param File $file
     *
     * @return void
     */
    public function save(File $file): void;

    /**
     * Find file by id
     *
     * @param int $id
     *
     * @return File|null
     */
    public function findOneById(int $id): ?File;
}
