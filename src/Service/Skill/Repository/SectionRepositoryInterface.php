<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\Repository;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Section;
use RashinMe\Service\Skill\Dto\SectionFilter;

interface SectionRepositoryInterface
{
    /**
     * Save section
     *
     * @param Section $section
     *
     * @return void
     */
    public function save(Section $section): void;

    /**
     * Delete section
     *
     * @param Section $section
     *
     * @return void
     */
    public function delete(Section $section): void;

    /**
     * Find section by id
     *
     * @param int $id
     *
     * @return Section|null
     */
    public function findOneById(int $id): ?Section;

    /**
     * Get collection of section
     *
     * @param SectionFilter $filter
     *
     * @return Collection<int, Section>
     */
    public function getSections(SectionFilter $filter): Collection;

    /**
     * Get count of sections
     *
     * @param SectionFilter $filter
     *
     * @return int
     */
    public function getCount(SectionFilter $filter): int;
}
