<?php

declare(strict_types=1);

namespace RashinMe\Service\Education;

use DateTime;
use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Education;
use RashinMe\Service\Education\Dto\DateData;
use RashinMe\Service\Education\Dto\EducationData;
use RashinMe\Service\Education\Filter\EducationFilter;
use RashinMe\Service\Education\Filter\EducationSort;
use RashinMe\Service\Education\Repository\EducationRepositoryInterface;

class EducationService
{
    public function __construct(
        private readonly EducationRepositoryInterface $educationRepository,
    ) {
    }

    /**
     * Add education
     *
     * @param EducationData $educationData
     *
     * @return Education
     */
    public function addEducation(EducationData $educationData): Education
    {
        $toDate = null;

        if ($educationData->to !== null) {
            $toDate = $this->getDateFromDateData($educationData->to);
        }

        $education = new Education(
            $educationData->institution,
            $educationData->faculty,
            $educationData->specialization,
            $this->getDateFromDateData($educationData->from),
            $toDate,
        );

        $this->educationRepository->save($education);

        return $education;
    }

    /**
     * Update education
     *
     * @param Education $education
     * @param EducationData $educationData
     *
     * @return Education
     */
    public function updateEducation(Education $education, EducationData $educationData): Education
    {
        $fromDate = $this->getDateFromDateData($educationData->from);

        $toDate = null;

        if ($educationData->to !== null) {
            $toDate = $this->getDateFromDateData($educationData->to);
        }

        $education->changeInstitution($educationData->institution);
        $education->changeFaculty($educationData->faculty);
        $education->changeSpecialization($educationData->specialization);
        $education->changeFromDate($fromDate);
        $education->changeToDate($toDate);

        $this->educationRepository->save($education);

        return $education;
    }

    /**
     * Get education by id
     *
     * @param int $id
     *
     * @return Education|null
     */
    public function getEducationById(int $id): ?Education
    {
        return $this->educationRepository->findOneById($id);
    }

    /**
     * Delete education
     *
     * @param Education $education
     *
     * @return void
     */
    public function deleteEducation(Education $education): void
    {
        $this->educationRepository->delete($education);
    }

    /**
     * Get list of educations
     *
     * @param EducationFilter $filter
     * @param EducationSort $sort
     *
     * @return Collection<int, Education>
     */
    public function getEducations(EducationFilter $filter, EducationSort $sort): Collection
    {
        return $this->educationRepository->getEducations($filter, $sort);
    }

    /**
     * Get count of educations
     *
     * @param EducationFilter $filter
     *
     * @return int
     */
    public function getCount(EducationFilter $filter): int
    {
        return $this->educationRepository->getCount($filter);
    }

    /**
     * @param DateData $dateData
     *
     * @return DateTime
     */
    private function getDateFromDateData(DateData $dateData): DateTime
    {
        return new DateTime("$dateData->year-$dateData->month");
    }
}
