<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use RashinMe\Entity\Link;
use RashinMe\Entity\Project;
use RashinMe\Service\Project\Repository\LinkRepositoryInterface;

/**
 * Link repository
 */
class LinkRepository implements LinkRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findLinksByProject(Project $project): array
    {
        return $this->getRepository()->findBy(["project" => $project]);
    }

    /**
     * @return ObjectRepository<Link>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Link::class);
    }
}
