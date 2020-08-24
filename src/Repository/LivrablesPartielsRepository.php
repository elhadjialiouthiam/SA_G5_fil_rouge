<?php

namespace App\Repository;

use App\Entity\LivrablesPartiels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivrablesPartiels|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivrablesPartiels|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivrablesPartiels[]    findAll()
 * @method LivrablesPartiels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrablesPartielsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivrablesPartiels::class);
    }

    // /**
    //  * @return LivrablesPartiels[] Returns an array of LivrablesPartiels objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LivrablesPartiels
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
