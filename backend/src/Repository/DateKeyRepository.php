<?php

namespace App\Repository;

use App\Entity\DateKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateKey[]    findAll()
 * @method DateKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateKey::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('dk')->getQuery();
    }

    // /**
    //  * @return DateKey[] Returns an array of DateKey objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DateKey
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
