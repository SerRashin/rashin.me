<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use RashinMe\Entity\Property;
use RashinMe\Service\Property\Dto\PropertyFilter;
use RashinMe\Service\Property\Repository\PropertyRepositoryInterface;

class PropertyRepository implements PropertyRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findByKey(string $key): ?Property
    {
        return $this->getRepository()->findOneBy(["key" => $key]);
    }

    /**
     * @inheritDoc
     */
    public function findByKeys(array $keys): Collection
    {
        $query = $this->createQueryBuilder()
            ->addSelect('prop')
            ->from(Property::class, 'prop');

        /** @var array<int, Property> $properties */
        $properties = $query
            ->where('prop.key IN(:keys)')
            ->setParameter('keys', array_values($keys))
            ->getQuery()
            ->getResult();

        return new ArrayCollection($properties);
    }

    public function getProperties(PropertyFilter $propertyFilter): Collection
    {
        $query = $this->createQueryBuilder()
            ->addSelect('prop')
            ->from(Property::class, 'prop');

        $fields = $propertyFilter->fields;

        if (count($fields) > 0) {
            $query->where('prop.key IN(:keys)')
                ->setParameter('keys', $fields);
        }


        /** @var array<int, Property> $properties */
        $properties = $query
            ->getQuery()
            ->getResult();

        return new ArrayCollection($properties);
    }

    /**
     * @inheritDoc
     */
    public function save(Property $configuration): void
    {
        $this->entityManager->persist($configuration);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function saveMany(Collection $configurations): void
    {
        $this->entityManager->beginTransaction();

        try {
            foreach ($configurations as $configuration) {
                $this->save($configuration);
            }

            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollBack();
            throw $e;
        }
    }

    /**
     * @return ObjectRepository<Property>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Property::class);
    }

    /**
     * @return QueryBuilder
     */
    private function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }
}
