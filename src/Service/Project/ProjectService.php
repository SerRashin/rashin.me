<?php

declare(strict_types=1);

namespace RashinMe\Service\Project;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Link;
use RashinMe\Entity\Project;
use RashinMe\Service\Error;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Project\Dto\ProjectData;
use RashinMe\Service\Project\Dto\ProjectFilter;
use RashinMe\Service\Project\Repository\ProjectRepositoryInterface;
use RashinMe\Service\Storage\StorageService;
use RashinMe\Service\Validation\ValidationServiceInterface;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly StorageService $storageService,
        private readonly TagService $tagService,
        private readonly LinkService $linkService,
    ) {
    }

    public function addProject(ProjectData $projectData): Project|ErrorInterface
    {
        $file = $this->storageService->getFileById($projectData->imageId);

        if ($file === null) {
            return new Error('Image not found');
        }

        $tags = $this->tagService->createTags($projectData->tags);

        $project = new Project(
            $projectData->name,
            $projectData->description,
            $file
        );

        foreach ($tags as $tag) {
            $project->addTag($tag);
        }

        foreach ($projectData->links as $link) {
            $project->addLink(new Link($project, $link->title, $link->url));
        }

        $this->projectRepository->save($project);

        return $project;
    }

    public function updateProject(Project $project, ProjectData $projectData): Project|ErrorInterface
    {
        $file = $this->storageService->getFileById($projectData->imageId);

        if ($file === null) {
            return new Error('Image not found');
        }

        $tags = $this->tagService->createTags($projectData->tags);
        $links = $this->linkService->createLinks($project, $projectData->links);

        $project->changeImage($file);
        $project->changeName($projectData->name);
        $project->changeDescription($projectData->description);
        $project->setTags($tags);
        $project->setLinks($links);

        $this->projectRepository->save($project);

        return $project;
    }

    /**
     * @param ProjectFilter $filter
     *
     * @return Collection<int, Project>
     */
    public function getProjects(ProjectFilter $filter): Collection
    {
        return $this->projectRepository->getProjects($filter);
    }

    /**
     * @param ProjectFilter $filter
     *
     * @return int
     */
    public function getCount(ProjectFilter $filter): int
    {
        return $this->projectRepository->getCount($filter);
    }

    /**
     * Delete project
     *
     * @param Project $project
     *
     * @return void
     */
    public function deleteProject(Project $project): void
    {
        $this->projectRepository->delete($project);
    }

    /**
     * Get project by id
     *
     * @param int $id
     *
     * @return Project|null
     */
    public function getProjectById(int $id): ?Project
    {
        return $this->projectRepository->findOneById($id);
    }
}
