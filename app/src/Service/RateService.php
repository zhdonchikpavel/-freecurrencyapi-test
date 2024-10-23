<?php

namespace App\Service;

use App\Entity\Currency;
use App\Entity\Rate;
use Doctrine\ORM\EntityManagerInterface;

class RateService
{
    /**
     * @var RateRepository
     */
    private $rateRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager) {
        $this->rateRepository = $this->entityManager->getRepository(Rate::class);
    }

    public function findAll() {
        return $this->rateRepository->findAll();
    }

    public function createOrUpdate(Rate $rate)
    {
        $currentRate = $this->getCurrentRate($rate->getSourceCurrency(), $rate->getTargetCurrency());
        if ($currentRate) {
            $currentRate->setRate($rate->getRate());
            $this->entityManager->persist($currentRate);
        } else {
            $this->entityManager->persist($rate);
        }

        $this->entityManager->flush();
    }

    public function getCurrentRate(Currency $sourceCurrency, Currency $targetCurrency): Rate | null
    {
        return $this->rateRepository->getCurrentRate($sourceCurrency, $targetCurrency);
    }
}
