<?php

declare(strict_types=1);

namespace RashinMe\Entity;

use DateTimeInterface;

class Job
{
    /**
     * @var int
     */
    private int $id;

    public function __construct(
        private string $name,
        private string $type,
        private string $description,
        private Company $company,
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function changeType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function changeDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function changeCompany(Company $company): void
    {
        $this->company = $company;
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
