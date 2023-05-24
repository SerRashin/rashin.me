<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\Tag;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Project\Repository\TagRepositoryInterface;

class TagRepositoryTest extends FunctionalTestCase
{
    private TagRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TagRepository($this->getEntityManager());
    }

    public function testSaveTag(): void
    {
        $tagNames = [
            'tag1',
            'TAG2',
        ];

        $tags = [];

        foreach ($tagNames as $tagName){
            $tags[] = new Tag($tagName);
        }

        $this->repository->saveTags($tags);

        $savedTags = $this->repository->findTagsByNames($tagNames);

        $this->assertSame($savedTags, $tags);
    }
}
