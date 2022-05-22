<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Commande $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Commande $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Commande[] Returns an array of Commande objects
    //  */
    
    public function findMenusAndCommande()
    {
       
        return $this->createQueryBuilder('c')
            ->innerJoin('c.menus', 'm')
            ->getQuery()->getResult();
    }
   
    public function findBurgersAndCommande()
    {
       
        return $this->createQueryBuilder('c')
            ->innerJoin('c.burgers', 'b')
            ->getQuery()->getResult();
    }

    public function findCommandeByDateAndEtat($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date = :val')
            ->andWhere('c.etat = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCommandeByDateAndEtatAndClient($value,$value2,$value3)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date = :val')
            ->andWhere('c.etat = :val2')
            ->andWhere('c.client = :val3')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->setParameter('val3', $value3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCommandeByEtatAndClient($value,$value3)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.etat = :val')
            ->andWhere('c.client = :val3')
            ->setParameter('val', $value)
            ->setParameter('val3', $value3)
            ->getQuery()
            ->getResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Commande
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
