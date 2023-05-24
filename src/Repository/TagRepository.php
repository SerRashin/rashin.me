<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use RashinMe\Entity\Tag;
use RashinMe\Service\Project\Repository\TagRepositoryInterface;

/**
 * Tag repository
 */
class TagRepository implements TagRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findTagsByNames(array $names): array
    {
        return $this->getRepository()->findBy(["name" => $names]);
    }

    /**
     * @inheritDoc
     */
    public function saveTags(array $tags): array
    {
        foreach ($tags as $tag) {
            $this->entityManager->persist($tag);
        }

        $this->entityManager->flush();

        return $tags;
    }

    /**
     * @return ObjectRepository<Tag>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Tag::class);
    }
}
