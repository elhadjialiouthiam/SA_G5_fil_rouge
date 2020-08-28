<?php

namespace App\Repository;

use App\Entity\Briefs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Briefs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Briefs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Briefs[]    findAll()
 * @method Briefs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Briefs::class);
    }

    // /**
    //  * @return Briefs[] Returns an array of Briefs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Briefs
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
