<?php

declare(strict_types=1);

namespace RashinMe\Service\Validation;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RashinMe\Service\ErrorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationServiceTest extends TestCase
{
    /**
     * @var (ValidatorInterface&MockObject)
     */
    private ValidatorInterface $validator;

    /**
     * @var ValidationService
     */
    private ValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->service = new ValidationService($this->validator);
    }

    public function testValidateIfDataInvalid(): void
    {
        $expectedErrorKey = 'some.path';
        $expectedErrorValue = 'some error';

        $violation = $this->createMock(ConstraintViolationInterface::class);

        $violation->expects($this->once())
            ->method('getMessage')
            ->willReturn($expectedErrorValue);

        $violation->expects($this->once())
            ->method('getPropertyPath')
            ->willReturn($expectedErrorKey);

        $violations = new ConstraintViolationList([$violation]);

        $someData = [];
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($someData))
            ->willReturn($violations);

        $error = $this->service->validate($someData);

        $this->assertInstanceOf(ErrorInterface::class, $error);
        $this->assertEquals('Validation Error', $error->getMessage());
        $this->assertEquals([$expectedErrorKey => $expectedErrorValue], $error->getDetails());
    }
}
