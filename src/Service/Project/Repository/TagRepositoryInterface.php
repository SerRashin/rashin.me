<?php

declare(strict_types=1);

namespace RashinMe\Service\Project\Repository;

use RashinMe\Entity\Tag;

interface TagRepositoryInterface
{
    /**
     * Find tags by names
     *
     * @param string[] $names
     *
     * @return Tag[]
     */
    public function findTagsByNames(array $names): array;

    /**
     * Save tags
     *
     * @param Tag[] $tags
     *
     * @return Tag[]
     */
    public function saveTags(array $tags): array;
}
