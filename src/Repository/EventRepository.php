<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findNextEvent()
    {
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.date > :date')
            ->setParameter('date', time())
            ->orderBy('e.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery();

        return $qb->execute()[0];
    }

}
