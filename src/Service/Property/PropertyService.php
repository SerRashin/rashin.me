<?php

declare(strict_types=1);

namespace RashinMe\Service\Property;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Property;
use RashinMe\Service\Property\Dto\PropertiesData;
use RashinMe\Service\Property\Dto\PropertyFilter;
use RashinMe\Service\Property\Repository\PropertyRepositoryInterface;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Validation\ValidationServiceInterface;

class PropertyService
{
    public function __construct(
        private readonly PropertyRepositoryInterface $configurationRepository,
        private readonly ValidationServiceInterface  $validationService,
    ) {
    }

    public function getProperty(string $name): Property
    {
        return $this->configurationRepository->findByKey($name);
    }


    public function getProperties(PropertyFilter $propertyFilter)
    {
        return $this->configurationRepository->getProperties($propertyFilter);
    }

    /**
     * @param PropertiesData $configurationData
     *
     * @return Collection<int, Property>|ErrorInterface
     */
    public function updateProperties(PropertiesData $configurationData): Collection|ErrorInterface
    {
        $validationError = $this->validationService->validate($configurationData);

        if ($validationError !== null) {
            return $validationError;
        }

        $keys = [];
        $propertiesData = [];

        foreach ($configurationData->properties as $property) {
            $key = $property->key;

            $keys[] = $key;
            $propertiesData[$key] = $property;
        }

        $properties = $this->configurationRepository->findByKeys($keys);

        foreach ($properties as $property) {
            $key = $property->getKey();
            $property->setValue($propertiesData[$key]->value);

            unset($propertiesData[$key]);
        }

        foreach ($propertiesData as $property) {
            $properties->add(new Property($property->key, $property->value));
        }

        $this->configurationRepository->saveMany($properties);

        return $properties;
    }
}
