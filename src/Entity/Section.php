<?php

declare(strict_types=1);

namespace RashinMe\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Section of skill
 */
class Section
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var Collection<int, Skill>
     */
    private Collection $skills;

    public function __construct(
        private string $name,
    ) {
        $this->id = 0;
        $this->skills = new ArrayCollection();
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
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }
}
