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

    public function findAll()
    {
        return $this->findBy(array(), array('date_played' => 'ASC'));
    }

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
            AND g.date_played BETWEEN ?1 AND ?2
            AND a.state LIKE \'GRATUIT\'            
            AND p.currency3=0
            AND p.currency4=0
            AND p.currency5=0
            AND p.currency6=0            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountjagQD($dm,$fm)
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
            AND a.code_adv LIKE \'ADV_18\'            
            AND p.currency3=0
            AND p.currency4=0
            AND p.currency5=0
            AND p.currency6=0            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountCltvCustomer($dm,$fm)
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
            AND g.date_played >= ?1            
            AND a.state LIKE \'PAYANT\'            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountArpuCustomer($dm,$fm)
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
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }
    
    public function findByCountCacCustomerParis($dm,$fm)
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
            AND ( a.cp LIKE \'75%\' OR a.cp LIKE \'77%\' OR a.cp LIKE \'78%\' OR a.cp LIKE \'91%\' OR a.cp LIKE \'92%\' OR a.cp LIKE \'93%\' OR a.cp LIKE \'94%\' OR a.cp LIKE \'95%\' )
            AND g.date_played >= ?1          
            AND a.state LIKE \'PAYANT\'
            AND p.currency3=0
            AND p.currency4=0            
            AND p.currency5=0
            AND p.currency6=0
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }
    
    public function findByCountCacCustomer($dm,$fm)
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
            AND g.date_played >= ?1          
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

    // comptes créés hors cac

    public function findByCountcch($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(p)
            FROM App\Entity\Player p            
            WHERE p.state NOT LIKE \'HIDDEN\'
            AND p.date_creation BETWEEN ?1 AND ?2           
            AND ( p.currency3>0 OR p.currency4>0 OR p.currency5>0 OR p.currency6>0 )
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountAdventurePayed($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(g)
            FROM App\Entity\Game g
            Join App\Entity\Adventure a
            WITH g.code_adv = a.code_adv
            Join App\Entity\Player p
            WITH g.id_player = p.id_player  
            WHERE p.state NOT LIKE \'HIDDEN\'            
            AND g.date_played BETWEEN ?1 AND ?2 
            AND a.state LIKE \'PAYANT\'           
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
            AND g.date_played >= ?1 
            AND a.state LIKE \'PAYANT\'                       
            GROUP BY p.id_player                  
            ')->setParameter(1, $dm);
        return $query->getResult();  
        
    }  
    
    public function findByCountc($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT distinct a.code_adv, a.name, count(distinct p.id_player)
            FROM App\Entity\Game g
            Join App\Entity\Adventure a
            WITH g.code_adv = a.code_adv
            Join App\Entity\Player p
            WITH g.id_player = p.id_player  
            WHERE p.state NOT LIKE \'HIDDEN\'
            AND g.date_played BETWEEN ?1 AND ?2         
            GROUP BY a.code_adv, a.name
            ORDER BY count(distinct p.id_player) DESC
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
