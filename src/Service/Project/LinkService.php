<?php

declare(strict_types=1);

namespace RashinMe\Service\Project;

use RashinMe\Entity\Link;
use RashinMe\Entity\Project;
use RashinMe\Service\Project\Dto\LinkData;
use RashinMe\Service\Project\Repository\LinkRepositoryInterface;

class LinkService
{
    public function __construct(
        private readonly LinkRepositoryInterface $linkRepository
    ) {
    }

    /**
     * Create links
     *
     * @param Project $project
     * @param LinkData[] $linksData
     *
     * @return iterable<Link>
     */
    public function createLinks(Project $project, array $linksData): iterable
    {
        $existsLinks = [];
        $newLinks = [];
        $changedLink = [];
        $links = $this->linkRepository->findLinksByProject($project);

        foreach ($links as $link) {
            $existsLinks[$link->getId()] = $link;
        }

        foreach ($linksData as $link) {
            $id = $link->id;

            if ($id !== null && isset($existsLinks[$id])) {
                $item = $existsLinks[$id];

                $item->changeTitle($link->title);
                $item->changeUrl($link->url);

                $changedLink[] = $item;
            } else {
                $newLinks[] = new Link($project, $link->title, $link->url);
            }
        }

        yield from $changedLink;
        yield from $newLinks;
    }
}
