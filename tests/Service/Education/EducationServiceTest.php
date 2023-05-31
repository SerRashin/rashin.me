<?php

declare(strict_types=1);

namespace RashinMe\Service\Education;

use DateTime;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RashinMe\Entity\Education;
use RashinMe\Service\Education\Dto\DateData;
use RashinMe\Service\Education\Dto\EducationData;
use RashinMe\Service\Education\Filter\EducationFilter;
use RashinMe\Service\Education\Filter\EducationSort;
use RashinMe\Service\Education\Repository\EducationRepositoryInterface;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Validation\ValidationError;

class EducationServiceTest extends TestCase
{
    /**
     * @var EducationRepositoryInterface&MockObject
     */
    private EducationRepositoryInterface $educationRepository;

    private EducationService $service;

    private EducationData $testData;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->educationRepository = $this->createMock(EducationRepositoryInterface::class);

        $this->service = new EducationService(
            $this->educationRepository,
        );

        $this->testData = new EducationData(
            institution: 'institution',
            faculty: 'faculty',
            specialization: 'specialization',
            from: new DateData(1, 2010),
            to: new DateData(1, 2010),
        );
    }

    public function testAddingEducationWithInvalidData(): void
    {
        $expectedError = new ValidationError("some error", []);

        $result = $this->service->addEducation($this->testData);

        $this->assertInstanceOf(ErrorInterface::class, $result);
        $this->assertSame($expectedError, $result);
    }

    public function testEducationSaving(): void
    {
        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn(null);

        $expectedEducation = null;
        $this->educationRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Education $education) use (&$expectedEducation): bool {
                $this->assertEquals($this->testData->institution, $education->getInstitution());
                $this->assertEquals($this->testData->faculty, $education->getFaculty());
                $this->assertEquals($this->testData->specialization, $education->getSpecialization());
                $this->assertEquals($this->testData->from->month, $education->getFromDate()->format('m'));
                $this->assertEquals($this->testData->from->year, $education->getFromDate()->format('Y'));

                $expectedEducation = $education;

                return true;
            }));

        $result = $this->service->addEducation($this->testData);

        $this->assertInstanceOf(Education::class, $result);
        $this->assertsame($expectedEducation, $result);
    }

    public function testUpdatingEducationWithInvalidData(): void
    {
        $educationEntity = new Education(
            'institution',
            'faculty',
            'specialization',
            new DateTime('now'),
            new DateTime('now'),
        );

        $expectedError = new ValidationError("some error", []);

        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn($expectedError);

        $result = $this->service->updateEducation($educationEntity, $this->testData);

        $this->assertInstanceOf(ErrorInterface::class, $result);
        $this->assertSame($expectedError, $result);
    }

    public function testEducationUpdating(): void
    {
        $educationEntity = new Education(
            'institution',
            'faculty',
            'specialization',
            new DateTime('now'),
            new DateTime('now'),
        );

        $this->validationService
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($this->testData))
            ->willReturn(null);

        $this->educationRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Education $education) use (&$expectedEducation): bool {
                $this->assertEquals($this->testData->institution, $education->getInstitution());
                $this->assertEquals($this->testData->faculty, $education->getFaculty());
                $this->assertEquals($this->testData->specialization, $education->getSpecialization());
                $this->assertEquals($this->testData->from->month, $education->getFromDate()->format('m'));
                $this->assertEquals($this->testData->from->year, $education->getFromDate()->format('Y'));

                $expectedEducation = $education;

                return true;
            }));

        $result = $this->service->updateEducation($educationEntity, $this->testData);

        $this->assertInstanceOf(Education::class, $result);
        $this->assertsame($educationEntity, $result);
    }

    public function testGetNotExistsEducationById(): void
    {
        $educationId = 123;

        $this->educationRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->identicalTo($educationId))
            ->willReturn(null);

        $education = $this->service->getEducationById($educationId);

        $this->assertNull($education);
    }
    public function testGetEducationById(): void
    {
        $educationId = 123;

        $expectedEducation = $this->createMock(Education::class);
        $this->educationRepository
            ->expects($this->once())
            ->method('findOneById')
            ->with($this->identicalTo($educationId))
            ->willReturn($expectedEducation);

        $education = $this->service->getEducationById($educationId);

        $this->assertEquals($expectedEducation, $education);
    }

    public function testDeleteEducation(): void
    {
        $educationEntity = new Education(
            'institution',
            'faculty',
            'specialization',
            new DateTime('now'),
            new DateTime('now'),
        );

        $this->educationRepository
            ->expects($this->once())
            ->method('delete')
            ->with($this->callback(function (Education $education) use (&$educationEntity): bool {
                $this->assertEquals($educationEntity, $education);

                return true;
            }));

        $this->service->deleteEducation($educationEntity);
    }

    public function testGetEducationsCount(): void
    {
        $expectedCount = 123;

        $filter = new EducationFilter(10, 0);

        $this->educationRepository
            ->expects($this->once())
            ->method('getCount')
            ->with($this->identicalTo($filter))
            ->willReturn($expectedCount);

        $result = $this->service->getCount($filter);

        $this->assertEquals($expectedCount, $result);
    }

    public function testGetEducations(): void
    {
        $expectedCollection = $this->createMock(Collection::class);

        $filter = new EducationFilter(10, 0);
        $sort = new EducationSort('id', 'asc');

        $this->educationRepository
            ->expects($this->once())
            ->method('getEducations')
            ->with($this->identicalTo($filter))
            ->willReturn($expectedCollection);

        $result = $this->service->getEducations($filter, $sort);

        $this->assertEquals($expectedCollection, $result);
    }
}
