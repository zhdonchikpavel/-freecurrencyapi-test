<?php

namespace App\Service\Adapter;

use App\Entity\Currency;
use App\Entity\Rate;
use App\Service\CurrencyRateLoaderInterface;
use App\Service\RateService;
use Exception;
use Fiber;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;

class CurrencyRateLoaderFreecurrencyapi implements CurrencyRateLoaderInterface
{
    private const API_URL = 'https://api.freecurrencyapi.com/v1';
    private const REQUESTS_PER_MINUTE_LIMIT = 10;

    private $defaultActiveCurrenciesCodesList = [];

    public function __construct(
        private string $freecurrencyapiKey,
        private string $defaultActiveCurrencies,
        private HttpClientInterface $client,
        private RateService $rateService
    ) {
        $this->defaultActiveCurrenciesCodesList = explode(',', $this->defaultActiveCurrencies);
    }

    public function loadCurrencies() {
        $response = $this->makeRequest('currencies');
        return $response->toArray()['data'] ?? [];
    }

    public function makeCurrency(array $rawCurrency): Currency {
        $currency = new Currency();
        $currency->setName($rawCurrency['name']);
        $currency->setCode($rawCurrency['code']);
        $currency->setDecimalDigits($rawCurrency['decimal_digits']);
        $currency->setIsActive(in_array($rawCurrency['code'], $this->defaultActiveCurrenciesCodesList));

        return $currency;
    }

    /**
     * @param Currency[] $currencies
     */
    public function loadRates($currencies) {
        $rates = [];
        $fibers = [];
        $chunkSize = self::REQUESTS_PER_MINUTE_LIMIT;
        $currenciesChunks = $chunkSize ? array_chunk($currencies, $chunkSize) : $currencies;
        $shouldWait = false;

        foreach ($currenciesChunks as $currenciesChunk) {
            if ($shouldWait) {
                // platform free plan count requests limitations
                sleep(70);
            }
            foreach ($currenciesChunk as $currency) {
                $fibers[] = new Fiber(function () use ($currency, $currencies, &$rates) {
                    print "-load for code {$currency->getCode()} \n";
                    $rates[$currency->getCode()] = $this->loadCurrencyRates($currency, $currencies);
                });
            }

            $shouldWait = true;

            foreach ($fibers as $fiber) {
                $fiber->start();
            }
        }

        $this->saveRates($currencies, $rates);
    }

    /**
     * @param Currency $sourceCurrency
     * @param Currency[] $targetCurrencies
     */
    private function loadCurrencyRates($sourceCurrency, $targetCurrencies) {
        $currencyCodes = array_map(fn($currency) => $currency->getCode(), $targetCurrencies);
        $response = $this->makeRequest(
            'latest',
            ['base_currency'=>$sourceCurrency->getCode(), 'currencies'=>implode(',', $currencyCodes)]
        );

        return $response->toArray()['data'] ?? [];
    }

    /**
     * @param Currency[] $currencies
     * @param array $rates
     */
    private function saveRates($currencies, $rates) {
        $currenciesByCode = [];
        foreach ($currencies as $currency) {
            $currenciesByCode[$currency->getCode()] = $currency;
        }

        foreach ($currenciesByCode as $currencyCode => $currency) {
            foreach ($rates[$currencyCode] as $targetCurrencyCode => $currencyRate) {
                $targetCurrency = $currenciesByCode[$targetCurrencyCode];

                print "handle rate $currencyCode] && $targetCurrencyCode] \n";

                if (!$targetCurrency) {
                    print "currency $targetCurrencyCode is missing in DB \n";
                    continue;
                }

                $rate = new Rate();
                $rate->setSourceCurrency($currency);
                $rate->setTargetCurrency($targetCurrency);
                $rate->setRate($currencyRate);
                $this->rateService->createOrUpdate($rate);
            }
        }

        // TODO: deactivate currency if no rates loaded
    }

    /**
     * @param string $endpoint
     * @return string
     */
    private function buildApiUrl($endpoint) {
        return rtrim(self::API_URL, '/') . "/$endpoint";
    }

    /**
     * @param string $endpoint
     * @param mixed[] $params
     */
    private function makeRequest($endpoint, $params = null) {
        $apiUrl = $this->buildApiUrl($endpoint);

        $response = $this->client->request(
            'GET',
            $apiUrl,
            [
                'headers' => [
                    'apikey' =>$this->freecurrencyapiKey,
                ],
                'query' => $params
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        if ($statusCode !== Response::HTTP_OK || !str_contains($contentType, 'application/json')) {
            throw new Exception($response->getContent(), $statusCode);
        }

        return $response;
    }
}
