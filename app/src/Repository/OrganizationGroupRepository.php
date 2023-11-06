<?php

namespace App\Repository;

use App\Entity\Organization\OrganizationGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganizationGroup>
 *
 * @method OrganizationGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganizationGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganizationGroup[]    findAll()
 * @method OrganizationGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationGroupRepository extends AbstractBasicRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganizationGroup::class);
    }

//    /**
//     * @return OrganizationGroup[] Returns an array of OrganizationGroup objects
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

//    public function findOneBySomeField($value): ?OrganizationGroup
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
