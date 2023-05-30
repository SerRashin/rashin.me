<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\Link;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Project\Repository\LinkRepositoryInterface;

class LinkRepositoryTest extends FunctionalTestCase
{
    private LinkRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new LinkRepository($this->getEntityManager());
    }


}
