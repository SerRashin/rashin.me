<?php

declare(strict_types=1);

namespace RashinMe\Entity;

class Link
{
    /**
     * @var int
     */
    private int $id;

    public function __construct(
        private readonly Project $project,
        private string $title,
        private string $url
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
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
    * @param string $title
    */
    public function changeTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function changeUrl(string $url): void
    {
        $this->url = $url;
    }
}
