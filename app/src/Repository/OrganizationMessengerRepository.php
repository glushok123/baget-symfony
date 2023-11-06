<?php

namespace App\Repository;

use App\Entity\Organization\OrganizationMessenger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganizationMessenger>
 *
 * @method OrganizationMessenger|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganizationMessenger|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganizationMessenger[]    findAll()
 * @method OrganizationMessenger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationMessengerRepository extends AbstractBasicRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganizationMessenger::class);
    }

//    /**
//     * @return OrganizationMessenger[] Returns an array of OrganizationMessenger objects
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

//    public function findOneBySomeField($value): ?OrganizationMessenger
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
