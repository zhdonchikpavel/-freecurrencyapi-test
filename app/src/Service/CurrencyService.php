<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyService
{
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager) {
        $this->currencyRepository = $this->entityManager->getRepository(Currency::class);
    }

    public function findAll() {
        return $this->currencyRepository->findAll();
    }

    public function findAllActive() {
        return $this->currencyRepository->findAllActive();
    }

    /**
     * @return Currency[]
     */
    public function currenciesListByCode() {
        $currencies = $this->findAll();
        $currenciesByCode = [];
        foreach ($currencies as $currency) {
            $currenciesByCode[$currency->getCode()] = $currency;
        }

        return $currenciesByCode;
    }

    public function save(Currency $currency)
    {
        $this->entityManager->persist($currency);
        $this->entityManager->flush();
    }
}
