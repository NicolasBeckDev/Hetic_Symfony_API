<?php

namespace App\Repository;

use App\Entity\Sign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sign[]    findAll()
 * @method Sign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sign::class);
    }

//    /**
//     * @return Sign[] Returns an array of Sign objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sign
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
