<?php

namespace App\Command;

use App\Service\CurrencyRateLoaderInterface;
use App\Service\CurrencyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:load-rates')]
class LoadRatesCommand extends Command
{
    public function __construct(
        private CurrencyRateLoaderInterface $currencyRateLoaderService,
        private CurrencyService $currencyService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('max_execution_time', 3600);

        $currencies = $this->currencyService->findAllActive();
        if (!count($currencies)) {
            return Command::FAILURE;
        }

        $this->currencyRateLoaderService->loadRates($currencies);

        return Command::SUCCESS;
    }
}