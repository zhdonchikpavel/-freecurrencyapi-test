<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Currency>
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * @return Currency[]
     */
    public function findAllActive()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getResult()
        ;
    }
}
