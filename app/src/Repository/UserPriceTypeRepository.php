<?php

namespace App\Repository;

use App\Entity\User\UserPriceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPriceType>
 *
 * @method UserPriceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPriceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPriceType[]    findAll()
 * @method UserPriceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPriceTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPriceType::class);
    }

//    /**
//     * @return UserPriceType[] Returns an array of UserPriceType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserPriceType
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
