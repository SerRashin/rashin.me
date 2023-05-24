<?php

declare(strict_types=1);

namespace RashinMe\Entity;

use DateTimeInterface;

class Education
{
    /**
     * @var int
     */
    private int $id;

    public function __construct(
        private string $institution,
        private string $faculty,
        private string $specialization,
        private DateTimeInterface $fromDate,
        private ?DateTimeInterface $toDate,
    ) {
        $this->id = 0;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getInstitution(): string
    {
        return $this->institution;
    }

    /**
     * @param string $institution
     */
    public function changeInstitution(string $institution): void
    {
        $this->institution = $institution;
    }

    /**
     * @return string
     */
    public function getFaculty(): string
    {
        return $this->faculty;
    }

    /**
     * @param string $faculty
     */
    public function changeFaculty(string $faculty): void
    {
        $this->faculty = $faculty;
    }

    /**
     * @return string
     */
    public function getSpecialization(): string
    {
        return $this->specialization;
    }

    /**
     * @param string $specialization
     */
    public function changeSpecialization(string $specialization): void
    {
        $this->specialization = $specialization;
    }

    /**
     * @return DateTimeInterface
     */
    public function getFromDate(): DateTimeInterface
    {
        return $this->fromDate;
    }

    /**
     * @param DateTimeInterface $fromDate
     */
    public function changeFromDate(DateTimeInterface $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return ?DateTimeInterface
     */
    public function getToDate(): ?DateTimeInterface
    {
        return $this->toDate;
    }

    /**
     * @param ?DateTimeInterface $toDate
     */
    public function changeToDate(?DateTimeInterface $toDate): void
    {
        $this->toDate = $toDate;
    }
}
