<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

     /**
     * @return Game[] Returns an array of Game objects
     */

    public function findByAdventure(int $curr)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT g
            FROM App\Entity\Game g            
            WHERE g.date_played >= \'2021-02-01\'
            ORDER BY g.date_played    
            ');
        return $query->getResult();  
        
    }
    public function findByCountjag($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(distinct p.id_player)
            FROM App\Entity\Game g
            Join App\Entity\Adventure a
            WITH g.code_adv = a.code_adv
            Join App\Entity\Player p
            WITH g.id_player = p.id_player  
            WHERE p.state NOT LIKE \'HIDDEN\'
            AND p.date_creation BETWEEN ?1 AND ?2
            AND a.state LIKE \'GRATUIT\'            
            AND p.currency3=0
            AND p.currency4=0
            AND p.currency5=0
            AND p.currency6=0            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountncnp($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(distinct p.id_player)
            FROM App\Entity\Game g
            Join App\Entity\Adventure a
            WITH g.code_adv = a.code_adv
            Join App\Entity\Player p
            WITH g.id_player = p.id_player  
            WHERE p.state NOT LIKE \'HIDDEN\'
            AND p.date_creation BETWEEN ?1 AND ?2
            AND a.state LIKE \'PAYANT\'
            AND ( a.cp LIKE \'75%\' OR a.cp LIKE \'77%\' OR a.cp LIKE \'78%\' OR a.cp LIKE \'91%\' OR a.cp LIKE \'92%\' OR a.cp LIKE \'93%\' OR a.cp LIKE \'94%\' OR a.cp LIKE \'95%\' )
            AND p.currency3=0
            AND p.currency4=0
            AND p.currency5=0
            AND p.currency6=0
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountncn($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(distinct p.id_player)
            FROM App\Entity\Game g
            Join App\Entity\Adventure a
            WITH g.code_adv = a.code_adv
            Join App\Entity\Player p
            WITH g.id_player = p.id_player  
            WHERE p.state NOT LIKE \'HIDDEN\'
            AND p.date_creation BETWEEN ?1 AND ?2
            AND a.state LIKE \'PAYANT\'
            AND p.currency3=0
            AND p.currency4=0
            AND p.currency5=0
            AND p.currency6=0
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountcc($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(p)
            FROM App\Entity\Player p            
            WHERE p.state NOT LIKE \'HIDDEN\'
            AND p.date_creation BETWEEN ?1 AND ?2
            AND p.currency3=0
            AND p.currency4=0
            AND p.currency5=0
            AND p.currency6=0
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountavpa($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(p)
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

    public function findByCountnc($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(distinct p.id_player)
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

    public function findByCountnum($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(g.date_played) as NUM FROM App\Entity\Game g 
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
            GROUP BY g.id_player            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    /*
    public function findOneBySomeField($value): ?Game
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
