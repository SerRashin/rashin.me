<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use RashinMe\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use RashinMe\Service\User\Dto\UserFilter;
use RashinMe\Service\User\Model\UserInterface;
use RashinMe\Service\User\Repository\UserRepositoryInterface;

/**
 * @inheritDoc
 */
final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(UserInterface $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(UserInterface $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function findOneByEmail(string $email): ?UserInterface
    {
        return $this->getRepository()->findOneBy(['email' => $email]);
    }

    /**
     * @inheritDoc
     */
    public function findOneById(int $id): ?UserInterface
    {
        return $this->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * @inheritDoc
     *
     * @return Collection<int, UserInterface>
     */
    public function getUsers(UserFilter $filter): Collection
    {
        $query = $this->createQueryBuilder()
            ->addSelect('u')
            ->from(User::class, 'u');

        if ($filter->limit !== null) {
            $query->setMaxResults($filter->limit);
        }

        if ($filter->offset !== 0) {
            $query->setFirstResult($filter->offset);
        }

        /** @var array<int, UserInterface> $users */
        $users = $query
            ->addOrderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();

        return new ArrayCollection($users);
    }

    /**
     * @inheritDoc
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getCount(UserFilter $filter): int
    {
        $query = $this->createQueryBuilder()
            ->addSelect('COUNT(u)')
            ->from(User::class, 'u');

        // @phpstan-ignore-next-line
        return (int) $query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return ObjectRepository<User>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }
}
