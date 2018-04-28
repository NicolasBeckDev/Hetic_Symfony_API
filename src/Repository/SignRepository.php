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

    public function getAveragesDelays()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT  
        FROM product p
        WHERE p.price > :price
        ORDER BY p.price ASC
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['price' => 1000]);

        return $qb->execute()[0];
    }
}
