<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //fonction permattant de trouver un ensemble de users non archivés suivant un profil donné
    public function findByProfil($profil)
    {
        return $this->createQueryBuilder('u')
        ->innerJoin('u.profil', 'p')
        ->where('p.libelle = :val1')
        ->andWhere('u.etat IS NULL')
        ->setParameter('val1', $profil)
        ->orderBy('u.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }

    //afficher un user suivant son profil et son id
    public function findOneByProfil($id, $profil): ?User
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.profil', 'p')
            ->where('u.id = :val1')
            ->andWhere('p.libelle = :val2')
            ->setParameter('val1', $id)
            ->setParameter('val2', $profil)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    //supprimer un user son profil et son id
    // public function deleteOneByProfil($id): ?User
    // {
    //     return $this->createQueryBuilder('u')
    //         ->delete('App\Entity\User', 'u')
    //         // ->innerJoin('u.profil', 'p')
    //         ->where('u.id = :val1')
    //         // ->andWhere('p.libelle = :val2')
    //         ->setParameter('val1', $id)
    //         // ->setParameter('val2', $profil)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //         ;
    // }

    //mise en place de la fonction de suppression d'un apprenanant
    //on recupere l'id de l'apprenant a supprimer
    // $id = $this->createQueryBuilder('u')
    //     ->innerJoin('u.profil', 'p')
    //     ->where('p.libelle = :val')
    //     ->setParameter('')

    // public function deleteByProfil(){
    //     ->
    // }


}
