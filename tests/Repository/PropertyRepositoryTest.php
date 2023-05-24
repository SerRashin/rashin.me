<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\Property;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\Property\Repository\PropertyRepositoryInterface;

class PropertyRepositoryTest extends FunctionalTestCase
{
    private PropertyRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PropertyRepository($this->getEntityManager());
    }

    public function testSaveProperty(): void
    {
        $propertyKey = 'some_property_key';
        $property = new Property(
            $propertyKey,
            'value',
        );

        $this->repository->save($property);

        $savedProperty = $this->repository->findByKey($propertyKey);

        $this->assertEquals($property, $savedProperty);
    }
}
