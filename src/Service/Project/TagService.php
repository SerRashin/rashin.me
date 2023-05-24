<?php

declare(strict_types=1);

namespace RashinMe\Service\Project;

use RashinMe\Entity\Tag;
use RashinMe\Service\Project\Repository\TagRepositoryInterface;

class TagService
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository,
    ) {
    }

    /**
     * Create tags
     *
     * @param string[] $names
     *
     * @return iterable<int, Tag>
     */
    public function createTags(array $names): iterable
    {
        $existingTags = $this->tagRepository->findTagsByNames($names);

        $existingTagsNames = [];
        foreach ($existingTags as $tag) {
            $existingTagsNames[] = $tag->getName();
        }

        $newTags = [];
        foreach ($names as $name) {
            if (!in_array($name, $existingTagsNames, true)) {
                $newTags[] = new Tag($name);
            }
        }

        if (count($newTags) > 0) {
            $this->tagRepository->saveTags($newTags);
        }

        yield from $existingTags;
        yield from $newTags;
    }
}
