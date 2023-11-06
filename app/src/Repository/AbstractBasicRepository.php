<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractBasicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function save($entity)
    {
        if (!$this->_em->isOpen()) {
            $this->registry->resetManager();
        }

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function remove($entity)
    {
        if (!$this->_em->isOpen()) {
            $this->registry->resetManager();
            $entity = $this->findOneBy(['id' => $entity->getId()]);
        }

        $this->_em->remove($entity);
        $this->_em->flush();
    }

}