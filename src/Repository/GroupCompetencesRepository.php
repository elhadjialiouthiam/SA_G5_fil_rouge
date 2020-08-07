<?php

namespace App\Repository;

use App\Entity\GroupCompetences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupCompetences|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupCompetences|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupCompetences[]    findAll()
 * @method GroupCompetences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupCompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupCompetences::class);
    }

    // /**
    //  * @return GroupCompetences[] Returns an array of GroupCompetences objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupCompetences
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
