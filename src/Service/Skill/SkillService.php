<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Skill;
use RashinMe\Repository\SkillRepository;
use RashinMe\Service\Error;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Skill\Dto\SkillData;
use RashinMe\Service\Skill\Dto\SkillFilter;
use RashinMe\Service\Storage\StorageService;
use RashinMe\Service\Validation\ValidationServiceInterface;

class SkillService
{
    public function __construct(
        private readonly ValidationServiceInterface $validationService,
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
        $validationError = $this->validationService->validate($skillData);

        if ($validationError !== null) {
            return $validationError;
        }

        $file = $this->storageService->getFileById($skillData->imageId ?? 0);

        if ($file === null) {
            return new Error('Validation Error', ['image' => 'Image id not found']);
        }

        $section = $this->sectionService->getSectionById($skillData->sectionId ?? 0);

        if ($section === null) {
            return new Error('Validation Error', ['section' => 'Section id not found']);
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
        $validationError = $this->validationService->validate($skillData);

        if ($validationError !== null) {
            return $validationError;
        }

        $file = $this->storageService->getFileById($skillData->imageId);

        if ($file === null) {
            return new Error("Image id not found");
        }

        $section = $this->sectionService->getSectionById($skillData->sectionId);

        if ($section === null) {
            return new Error("Section id not found");
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
     *
     * @return Collection<int, Skill>
     */
    public function getSkills(SkillFilter $filter): Collection
    {
        return $this->skillRepository->getSkills($filter);
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
