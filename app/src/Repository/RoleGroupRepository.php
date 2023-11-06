<?php

namespace App\Repository;

use App\Entity\RoleGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoleGroup>
 *
 * @method RoleGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoleGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoleGroup[]    findAll()
 * @method RoleGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoleGroup::class);
    }

//    /**
//     * @return SecurityGroup[] Returns an array of SecurityGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SecurityGroup
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
