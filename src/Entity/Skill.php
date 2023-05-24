<?php

declare(strict_types=1);

namespace RashinMe\Entity;

class Skill
{
    /**
     * @var int
     */
    private int $id;

    /**
     * Ctor.
     *
     * @param string $name
     * @param Section $section
     * @param File $image
     * @param string $description
     */
    public function __construct(
        private string $name,
        private Section $section,
        private File $image,
        private string $description,
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
     * @return Section
     */
    public function getSection(): Section
    {
        return $this->section;
    }

    /**
     * @param Section $section
     */
    public function changeSection(Section $section): void
    {
        $this->section = $section;
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
     * @return File
     */
    public function getImage(): File
    {
        return $this->image;
    }

    /**
     * @param File $image
     */
    public function changeImage(File $image): void
    {
        $this->image = $image;
    }
}
