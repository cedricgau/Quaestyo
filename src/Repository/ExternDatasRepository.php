<?php

namespace App\Repository;

use App\Entity\ExternDatas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExternDatas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternDatas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternDatas[]    findAll()
 * @method ExternDatas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternDatasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternDatas::class);
    }

    /**
    * @return ExternDatas[] Returns an array of ExternDatas objects
    */

    public function findByCountdepex($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e.advert
            FROM App\Entity\ExternDatas e            
            WHERE e.date_payed BETWEEN ?1 AND ?2            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountdepex2($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e.CA
            FROM App\Entity\ExternDatas e            
            WHERE e.date_payed BETWEEN ?1 AND ?2            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountdepex3($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e.download
            FROM App\Entity\ExternDatas e            
            WHERE e.date_payed BETWEEN ?1 AND ?2            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }

    public function findByCountdepex4($dm,$fm)
    {   
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT e.uninstall
            FROM App\Entity\ExternDatas e            
            WHERE e.date_payed BETWEEN ?1 AND ?2            
            ')->setParameter(1, $dm)->setParameter(2, $fm);
        return $query->getResult();  
        
    }
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExternDatas
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
