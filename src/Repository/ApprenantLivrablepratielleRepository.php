<?php

namespace App\Repository;

use App\Entity\ApprenantLivrablepratielle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApprenantLivrablepratielle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApprenantLivrablepratielle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApprenantLivrablepratielle[]    findAll()
 * @method ApprenantLivrablepratielle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantLivrablepratielleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApprenantLivrablepratielle::class);
    }

    // /**
    //  * @return ApprenantLivrablepratielle[] Returns an array of ApprenantLivrablepratielle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApprenantLivrablepratielle
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
