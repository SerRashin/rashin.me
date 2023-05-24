<?php

declare(strict_types=1);

namespace RashinMe\Service\Property\Repository;

use Doctrine\Common\Collections\Collection;
use RashinMe\Entity\Property;
use RashinMe\Service\Property\Dto\PropertyFilter;

interface PropertyRepositoryInterface
{
    /**
     * Find property by key
     *
     * @param string $key
     *
     * @return Property|null
     */
    public function findByKey(string $key): ?Property;

    /**
     * Find properties by keys
     *
     * @param string[] $keys
     *
     * @return Collection<int, Property>
     */
    public function findByKeys(array $keys): Collection;

    /**
     * Save configuration
     *
     * @param Property $configuration
     *
     * @return void
     */
    public function save(Property $configuration): void;

    /**
     * Save configurations
     *
     * @param Collection<Property> $configurations
     *
     * @return void
     */
    public function saveMany(Collection $configurations): void;

    /**
     * @param PropertyFilter $propertyFilter
     *
     * @return Collection<int, Property>
     */
    public function getProperties(PropertyFilter $propertyFilter): Collection;
}
