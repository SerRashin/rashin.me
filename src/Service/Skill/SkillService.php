<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Skill;
use RashinMe\Repository\SkillRepository;
use RashinMe\Service\Error;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Skill\Dto\SkillData;
use RashinMe\Service\Skill\Filter\SkillFilter;
use RashinMe\Service\Skill\Filter\SkillSort;
use RashinMe\Service\Storage\StorageService;

class SkillService
{
    public function __construct(
        private readonly SkillRepository $skillRepository,
        private readonly StorageService $storageService,
        private readonly SectionService $sectionService,
    ) {
    }

    /**
     * Add skill
     *
     * @param SkillData $skillData
     *
     * @return Skill|ErrorInterface
     */
    public function addSkill(SkillData $skillData): Skill|ErrorInterface
    {
        $file = $this->storageService->getFileById($skillData->imageId);

        if ($file === null) {
            return new Error('Image not found');
        }

        $section = $this->sectionService->getSectionById($skillData->sectionId);

        if ($section === null) {
            return new Error('Section not found');
        }

        $skill = new Skill(
            $skillData->name,
            $section,
            $file,
            $skillData->description,
        );

        $this->skillRepository->save($skill);

        return $skill;
    }

    /**
     * Update skill
     *
     * @param SkillData $skillData
     * @param Skill $skill
     *
     * @return Skill|ErrorInterface
     */
    public function updateSkill(SkillData $skillData, Skill $skill): Skill|ErrorInterface
    {
        $file = $this->storageService->getFileById($skillData->imageId);

        if ($file === null) {
            return new Error("Image not found");
        }

        $section = $this->sectionService->getSectionById($skillData->sectionId);

        if ($section === null) {
            return new Error("Section not found");
        }

        $skill->changeName($skillData->name);
        $skill->changeImage($file);
        $skill->changeSection($section);
        $skill->changeDescription($skillData->description);

        $this->skillRepository->save($skill);

        return $skill;
    }

    /**
     * Delete skill
     *
     * @param Skill $skill
     *
     * @return void
     */
    public function deleteSkill(Skill $skill): void
    {
        $this->skillRepository->delete($skill);
    }

    /**
     * Get skill by id
     *
     * @param int $id
     *
     * @return Skill|null
     */
    public function getSkillById(int $id): ?Skill
    {
        return $this->skillRepository->findOneById($id);
    }

    /**
     * Get skills
     *
     * @param SkillFilter $filter
     * @param SkillSort $sort
     *
     * @return Collection<int, Skill>
     */
    public function getSkills(SkillFilter $filter, SkillSort $sort): Collection
    {
        return $this->skillRepository->getSkills($filter, $sort);
    }

    /**
     * Get skills count
     *
     * @param SkillFilter $filter
     *
     * @return int
     */
    public function getCount(SkillFilter $filter): int
    {
        return $this->skillRepository->getCount($filter);
    }
}
