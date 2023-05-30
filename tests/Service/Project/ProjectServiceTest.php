<?php

declare(strict_types=1);

namespace RashinMe\Service\Project;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Project\Dto\ProjectData;
use RashinMe\Service\Project\Repository\ProjectRepositoryInterface;
use RashinMe\Service\Storage\StorageService;
use RashinMe\Service\Validation\ValidationError;
use RashinMe\Service\Validation\ValidationServiceInterface;

class ProjectServiceTest extends TestCase
{
    /**
     * @var ProjectRepositoryInterface&MockObject
     */
    private ProjectRepositoryInterface $jobRepository;

    /**
     * @var ValidationServiceInterface&MockObject
     */
    private ValidationServiceInterface $validationService;

    /**
     * @var StorageService&MockObject
     */
    private StorageService $storageService;

    /**
     * @var TagService&MockObject
     */
    private TagService $tagService;

    /**
     * @var LinkService&MockObject
     */
    private LinkService $linkService;

    private ProjectService $service;

    private ProjectData $testData;

    /**
     * @return void
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->jobRepository = $this->createMock(ProjectRepositoryInterface::class);
        $this->validationService = $this->createMock(ValidationServiceInterface::class);
        $this->storageService = $this->createMock(StorageService::class);
        $this->tagService = $this->createMock(TagService::class);
        $this->linkService = $this->createMock(LinkService::class);

        $this->service = new ProjectService(
            $this->jobRepository,
            $this->validationService,
            $this->storageService,
            $this->tagService,
            $this->linkService,
        );

        $this->testData = new ProjectData(
            'name',
            'description',
            1,
        );
    }

    public function testAddingProjectWithInvalidData(): void
    {
        $expectedError = new ValidationError("some error", []);

        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn($expectedError);

        $result = $this->service->addProject($this->testData);

        $this->assertInstanceOf(ErrorInterface::class, $result);
        $this->assertSame($expectedError, $result);
    }
}
