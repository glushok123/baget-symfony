<?php

namespace App\Repository;

use App\Entity\User\UserManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserManager>
 *
 * @method UserManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserManager[]    findAll()
 * @method UserManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserManager::class);
    }

//    /**
//     * @return UserManager[] Returns an array of UserManager objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserManager
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
