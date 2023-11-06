<?php

namespace App\Repository;

use App\Entity\Appeal\AppealMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppealMessage>
 *
 * @method AppealMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppealMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppealMessage[]    findAll()
 * @method AppealMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppealMessageRepository extends AbstractBasicRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppealMessage::class);
    }

//    /**
//     * @return AppealMessage[] Returns an array of AppealMessage objects
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

//    public function findOneBySomeField($value): ?AppealMessage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
