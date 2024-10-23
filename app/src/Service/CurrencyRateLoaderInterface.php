<?php

namespace App\Service;

use App\Entity\Currency;

interface CurrencyRateLoaderInterface
{
    /**
     * @return mixed[]
     */
    public function loadCurrencies();

    /**
    * @param Currency[] $currencies
    */
    public function loadRates($currencies);

    public function makeCurrency(array $rawCurrency): Currency;
}
