<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\Repository;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Skill;
use RashinMe\Service\Skill\Dto\SkillFilter;

interface SkillRepositoryInterface
{
    /**
     * Save skill
     *
     * @param Skill $skill
     *
     * @return void
     */
    public function save(Skill $skill): void;

    /**
     * Delete skill
     *
     * @param Skill $skill
     *
     * @return void
     */
    public function delete(Skill $skill): void;

    /**
     * Find skill by id
     *
     * @param int $id
     *
     * @return Skill|null
     */
    public function findOneById(int $id): ?Skill;

    /**
     * Get collection of skills
     *
     * @param SkillFilter $filter
     *
     * @return Collection<int, Skill>
     */
    public function getSkills(SkillFilter $filter): Collection;

    /**
     * Get count of skills
     *
     * @param SkillFilter $filter
     *
     * @return int
     */
    public function getCount(SkillFilter $filter): int;
}
