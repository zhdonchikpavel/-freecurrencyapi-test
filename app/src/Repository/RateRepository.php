<?php

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rate>
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function getCurrentRate($sourceCurrency, $targetCurrency): Rate | null
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.sourceCurrency = :sourceCurrency')
            ->andWhere('r.targetCurrency = :targetCurrency')
            ->setParameter('sourceCurrency', $sourceCurrency->getId())
            ->setParameter('targetCurrency', $targetCurrency->getId())
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
