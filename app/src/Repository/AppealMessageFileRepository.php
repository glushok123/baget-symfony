<?php

namespace App\Repository;

use App\Entity\Appeal\AppealMessageFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppealMessageFile>
 *
 * @method AppealMessageFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppealMessageFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppealMessageFile[]    findAll()
 * @method AppealMessageFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppealMessageFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppealMessageFile::class);
    }

//    /**
//     * @return AppealMessageFile[] Returns an array of AppealMessageFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AppealMessageFile
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
