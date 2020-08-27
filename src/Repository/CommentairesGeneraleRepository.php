<<<<<<< HEAD
<?php

namespace App\Repository;

use App\Entity\CommentairesGenerale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentairesGenerale|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentairesGenerale|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentairesGenerale[]    findAll()
 * @method CommentairesGenerale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesGeneraleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentairesGenerale::class);
    }

    // /**
    //  * @return CommentairesGenerale[] Returns an array of CommentairesGenerale objects
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
    public function findOneBySomeField($value): ?CommentairesGenerale
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //requette permettant de recuperer les jours dont il a eut des commentaires
    //d'un utilisateur

    public function getDay($user, $chat){
        return $this->createQueryBuilder('c')
            ->select('SUBSTRING(c.date, 1, 10) as day')
            ->where('c.user = :val1')
            ->andWhere('c.chatgeneral = :val2')
            ->setParameter('val1', $user)
            ->setParameter('val2', $chat)
            ->orderBy('c.date', 'ASC')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
        ;
    }

    //recuperer les msg d'un jour d'un utilisateur d'un chat general
    public function getCommentsOfADay($day, $user, $chat){
        return $this->createQueryBuilder('c')
            ->select('c as msg')
            ->orderBy('c.date', 'ASC')
            ->where('SUBSTRING(c.date, 1, 10) = :val1')
            ->andWhere('c.user = :val2')
            ->andWhere('c.chatgeneral = :val3')
            ->setParameter('val1', $day)
            ->setParameter('val2', $user)
            ->setParameter('val3', $chat)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getDayOfComments($chat){
        return $this->createQueryBuilder('c')
            ->select('SUBSTRING(c.date, 1, 10) as day')
            // ->where('c.user = :val1')
            ->andWhere('c.chatgeneral = :val2')
            // ->setParameter('val1', $user)
            ->setParameter('val2', $chat)
            ->orderBy('c.date', 'ASC')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCommentsOfADayAllUsers($day, $chat){
        return $this->createQueryBuilder('c')
            ->select('c as msg')
            ->orderBy('c.date', 'ASC')
            ->where('SUBSTRING(c.date, 1, 10) = :val1')
            // ->andWhere('c.user = :val2')
            ->andWhere('c.chatgeneral = :val3')
            ->setParameter('val1', $day)
            // ->setParameter('val2', $user)
            ->setParameter('val3', $chat)
            ->getQuery()
            ->getResult()
        ;
    }
}
=======
<?php

namespace App\Repository;

use App\Entity\CommentairesGenerale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentairesGenerale|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentairesGenerale|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentairesGenerale[]    findAll()
 * @method CommentairesGenerale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesGeneraleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentairesGenerale::class);
    }

    // /**
    //  * @return CommentairesGenerale[] Returns an array of CommentairesGenerale objects
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
    public function findOneBySomeField($value): ?CommentairesGenerale
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //requette permettant de recuperer les jours dont il a eut des commentaires
    //d'un utilisateur

    public function getDay($user, $chat){
        return $this->createQueryBuilder('c')
            ->select('SUBSTRING(c.date, 1, 10) as day')
            ->where('c.user = :val1')
            ->andWhere('c.chatgeneral = :val2')
            ->setParameter('val1', $user)
            ->setParameter('val2', $chat)
            ->orderBy('c.date', 'ASC')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
        ;
    }

    //recuperer les msg d'un jour d'un utilisateur d'un chat general
    public function getCommentsOfADay($day, $user, $chat){
        return $this->createQueryBuilder('c')
            ->select('c as msg')
            ->orderBy('c.date', 'ASC')
            ->where('SUBSTRING(c.date, 1, 10) = :val1')
            ->andWhere('c.user = :val2')
            ->andWhere('c.chatgeneral = :val3')
            ->setParameter('val1', $day)
            ->setParameter('val2', $user)
            ->setParameter('val3', $chat)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getDayOfComments($chat){
        return $this->createQueryBuilder('c')
            ->select('SUBSTRING(c.date, 1, 10) as day')
            // ->where('c.user = :val1')
            ->andWhere('c.chatgeneral = :val2')
            // ->setParameter('val1', $user)
            ->setParameter('val2', $chat)
            ->orderBy('c.date', 'ASC')
            ->groupBy('day')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCommentsOfADayAllUsers($day, $chat){
        return $this->createQueryBuilder('c')
            ->select('c as msg')
            ->orderBy('c.date', 'ASC')
            ->where('SUBSTRING(c.date, 1, 10) = :val1')
            // ->andWhere('c.user = :val2')
            ->andWhere('c.chatgeneral = :val3')
            ->setParameter('val1', $day)
            // ->setParameter('val2', $user)
            ->setParameter('val3', $chat)
            ->getQuery()
            ->getResult()
        ;
    }
}
>>>>>>> 05c02e8f4000920d6fa02d5b7a7e346f9896a230
