<?php

declare(strict_types=1);

namespace RashinMe\Service\Education\Repository;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Education;
use RashinMe\Service\Education\Filter\EducationFilter;
use RashinMe\Service\Education\Filter\EducationSort;

interface EducationRepositoryInterface
{
    /**
     * Save education
     *
     * @param Education $education
     *
     * @return void
     */
    public function save(Education $education): void;

    /**
     * Delete education
     *
     * @param Education $education
     *
     * @return void
     */
    public function delete(Education $education): void;

    /**
     * Find education by id
     *
     * @param int $id
     *
     * @return Education|null
     */
    public function findOneById(int $id): ?Education;

    /**
     * Get educations collection
     *
     * @param EducationFilter $filter
     * @param EducationSort $sort
     *
     * @return Collection<int, Education>
     */
    public function getEducations(EducationFilter $filter, EducationSort $sort): Collection;

    /**
     * Get educations count
     *
     * @param EducationFilter $filter
     *
     * @return int
     */
    public function getCount(EducationFilter $filter): int;
}
