<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Section;
use RashinMe\Service\Skill\Dto\SectionData;
use RashinMe\Service\Skill\Filter\SectionFilter;
use RashinMe\Service\Skill\Filter\SectionSort;
use RashinMe\Service\Skill\Repository\SectionRepositoryInterface;

class SectionService
{
    public function __construct(
        private readonly SectionRepositoryInterface $sectionRepository,
    ) {
    }
    public function addSection(SectionData $sectionData): Section
    {
        $section = new Section(
            $sectionData->name
        );

        $this->sectionRepository->save($section);

        return $section;
    }

    public function updateSection(SectionData $sectionData, Section $section): Section
    {
        $section->changeName($sectionData->name);

        $this->sectionRepository->save($section);

        return $section;
    }

    public function deleteSection(Section $section): void
    {
        $this->sectionRepository->delete($section);
    }

    public function getSectionById(int $sectionId): ?Section
    {
        return $this->sectionRepository->findOneById($sectionId);
    }

    /**
     * @param SectionFilter $filter
     * @param SectionSort $sort
     *
     * @return Collection<int, Section>
     */
    public function getSections(SectionFilter $filter, SectionSort $sort): Collection
    {
        return $this->sectionRepository->getSections($filter, $sort);
    }

    /**
     * @param SectionFilter $filter
     *
     * @return int
     */
    public function getCount(SectionFilter $filter): int
    {
        return $this->sectionRepository->getCount($filter);
    }
}
