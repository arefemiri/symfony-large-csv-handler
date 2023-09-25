<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPaginatedData(array $filters)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->select("u.id, u.username, u.employeeId, CONCAT(u.firstName, ' ',u.lastName) as fullName, u.gender, u.email");

        if (isset($filters['pageIndex']) && isset($filters['pageSize'])) {
            $queryBuilder->setMaxResults($filters['pageSize'])
                ->setFirstResult(($filters['pageIndex'] - 1) * $filters['pageSize']);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
