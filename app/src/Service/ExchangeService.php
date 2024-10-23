<?php

namespace App\Service;

use App\Entity\Currency;
use App\Service\RateService;

class ExchangeService
{
    public function __construct(private RateService $rateService) {}

    public function convert(Currency $sourceCurrency, Currency $targetCurrency, float $amount) {
        $rate = $this->rateService->getCurrentRate($sourceCurrency, $targetCurrency);

        return round($amount * $rate->getRate(), $targetCurrency->getDecimalDigits());
    }
}
