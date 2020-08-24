<?php

namespace App\Repository;

use App\Entity\BriefPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefPromo[]    findAll()
 * @method BriefPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefPromo::class);
    }

    // /**
    //  * @return BriefPromo[] Returns an array of BriefPromo objects
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
    public function findOneBySomeField($value): ?BriefPromo
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
