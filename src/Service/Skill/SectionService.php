<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Section;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Skill\Dto\SectionData;
use RashinMe\Service\Skill\Dto\SectionFilter;
use RashinMe\Service\Skill\Repository\SectionRepositoryInterface;
use RashinMe\Service\Validation\ValidationServiceInterface;

class SectionService
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
        private readonly SectionRepositoryInterface $sectionRepository,
    ) {
    }
    public function addSection(SectionData $sectionData): Section|ErrorInterface
    {
        $validationError = $this->validationService->validate($sectionData);

        if ($validationError !== null) {
            return $validationError;
        }

        $section = new Section(
            $sectionData->name
        );

        $this->sectionRepository->save($section);

        return $section;
    }

    public function updateSection(SectionData $sectionData, Section $section): Section|ErrorInterface
    {
        $validationError = $this->validationService->validate($sectionData);

        if ($validationError !== null) {
            return $validationError;
        }

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
     *
     * @return Collection<int, Section>
     */
    public function getSections(SectionFilter $filter): Collection
    {
        return $this->sectionRepository->getSections($filter);
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
