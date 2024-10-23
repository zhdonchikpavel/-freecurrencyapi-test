<?php

namespace App\Command;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use App\Service\CurrencyRateLoaderInterface;
use App\Service\CurrencyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:load-currencies')]
class LoadCurrenciesCommand extends Command
{
    public function __construct(
        private CurrencyRateLoaderInterface $currencyRateLoaderService,
        private CurrencyService $currencyService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $availableCurrencies = $this->currencyRateLoaderService->loadCurrencies();
        if (!count($availableCurrencies)) {
            return Command::FAILURE;
        }

        $existingCurrencies = $this->currencyService->currenciesListByCode();
        foreach ($availableCurrencies as $rawCurrency) {
            $currency = $this->currencyRateLoaderService->makeCurrency($rawCurrency);
            if (!array_key_exists($currency->getCode(), $existingCurrencies)) {
                $this->currencyService->save($currency);
            }
        }

        return Command::SUCCESS;
    }
}