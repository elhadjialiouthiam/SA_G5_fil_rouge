<?php

namespace App\Repository;

use App\Entity\ChatGeneral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatGeneral|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatGeneral|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatGeneral[]    findAll()
 * @method ChatGeneral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatGeneralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatGeneral::class);
    }

    // /**
    //  * @return ChatGeneral[] Returns an array of ChatGeneral objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChatGeneral
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
