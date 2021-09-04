<?php

namespace App\Repository;

use App\Entity\Adventure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adventure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adventure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adventure[]    findAll()
 * @method Adventure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdventureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adventure::class);
    }

    public function findByCountadv($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(g.id_player)
            FROM App\Entity\Game g
            Join App\Entity\Adventure a
            WITH g.code_adv = a.code_adv
            Join App\Entity\Player p
            WITH g.id_player = p.id_player  
            WHERE p.state NOT LIKE \'HIDDEN\'
            AND g.date_played BETWEEN ?1 AND ?2            
            AND a.state LIKE \'PAYANT\'
            AND p.currency3=0
            AND p.currency4=0
            AND p.currency5=0
            AND p.currency6=0      
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    // /**
    //  * @return Adventure[] Returns an array of Adventure objects
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
    public function findOneBySomeField($value): ?Adventure
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
