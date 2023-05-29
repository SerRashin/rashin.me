<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Entity\Link;
use RashinMe\Entity\Project;
use RashinMe\Entity\Tag;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class ViewControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/projects/%s';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testProjectNotFound(): void
    {
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Project not found',
        ]);
    }

    public function testProjectView(): void
    {
        $project = $this->createProject();

        $this->sendRequest('GET', sprintf(self::API_URL, $project->getId()));

        $this->assertStatusCodeEqualsTo(200);
        $this->assertJsonEqualsData([
            'id' => $project->getId(),
            'name' => $project->getName(),
            'image' => [
                'id' => $project->getImage()->getId(),
                'src' => $project->getImage()->getPath() . $project->getImage()->getName(),
            ],
            'description' => $project->getDescription(),
            'tags' => array_map(
                function (Tag $tag): string {
                    return $tag->getName();
                },
                $project->getTags()
            ),
            'links' => array_map(
                function (Link $link): array {
                    return [
                        'id' => $link->getId(),
                        'title' => $link->getTitle(),
                        'url' => $link->getUrl(),
                    ];
                },
                $project->getLinks()
            )
        ]);
    }

    private function createProject(): Project
    {
        $file = $this->createStorageFile();

        $project = new Project(
            'name',
            'description',
            $file,
        );

        $project->addTag(new Tag('one'));
        $project->addTag(new Tag('two'));


        $this->saveEntities($project);

        $project->addLink(new Link(
            $project,
            'some link',
            'some url',
        ));

        $this->saveEntities($project);

        return $project;
    }
}
