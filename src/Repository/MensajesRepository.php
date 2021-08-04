<?php

namespace App\Repository;

use App\Entity\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mensajes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mensajes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mensajes[]    findAll()
 * @method Mensajes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MensajesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensajes::class);
    }

    // /**
    //  * @return Mensajes[] Returns an array of Mensajes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mensajes
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
