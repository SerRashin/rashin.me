<?php

declare(strict_types=1);

namespace RashinMe\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Project
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var Collection<int, Link>
     */
    private Collection $links;

    /**
     * @var Collection<int, Tag>
     */
    private Collection $tags;

    /**
     * @param string $name
     * @param string $description
     */
    public function __construct(
        private string $name,
        private string $description,
        private File $image,
    ) {
        $this->id = 0;
        $this->links = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Add link
     *
     * @param Link $link
     *
     * @return void
     */
    public function addLink(Link $link): void
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
        }
    }

    /**
     * Remove link
     *
     * @param Link $link
     *
     * @return void
     */
    public function deleteLink(Link $link): void
    {
        if ($this->links->contains($link)) {
            $this->links->removeElement($link);
        }
    }

    /**
     * Set links
     *
     * @param iterable<Link> $links
     *
     * @return void
     */
    public function setLinks(iterable $links): void
    {
        $this->links->clear();

        foreach ($links as $link) {
            $this->links->add($link);
        }
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links->toArray();
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    /**
     * @param iterable<int, Tag> $tags
     *
     * @return void
     */
    public function setTags(iterable $tags): void
    {
        $this->tags->clear();

        foreach ($tags as $tag) {
            $this->tags->add($tag);
        }
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags->toArray();
    }
}
