<?php

namespace App\Repository;

use App\Entity\CompetencesValide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetencesValide|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetencesValide|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetencesValide[]    findAll()
 * @method CompetencesValide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetencesValideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetencesValide::class);
    }

    // /**
    //  * @return CompetencesValide[] Returns an array of CompetencesValide objects
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
    public function findOneBySomeField($value): ?CompetencesValide
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
