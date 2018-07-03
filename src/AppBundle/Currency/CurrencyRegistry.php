<?php
namespace AppBundle\Currency;

use AppBundle\Entity\CurrencyExchangeRate;
use AppBundle\Settings\Settings;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Component\VarDumper\VarDumper;

class CurrencyRegistry
{
    /**
     * @var Settings
     */
    private $globalSettings;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ExchangeRatesTable
     */
    private $exchangeRatesTable;

    /**
     * CurrencyRegistry constructor.
     * @param Settings $globalSettings
     * @param EntityManager $entityManager
     */
    public function __construct(Settings $globalSettings, EntityManager $entityManager)
    {
        $this->globalSettings = $globalSettings;
        $this->entityManager = $entityManager;
    }

    public function getBaseCurrency()
    {
        return $this->globalSettings->getBaseCurrency();
    }

    public function getEnabledCurrencies()
    {
        return ['EUR', 'USD'];
    }


    public function createExchangeRatesTable()
    {
        $table = [];
        foreach($this->getEnabledCurrencies() as $currencyCode) {
            $table[$currencyCode] = [];
        }
        /** @var CurrencyExchangeRate[] $exchangeRates */
        $exchangeRates = $this->entityManager->getRepository(CurrencyExchangeRate::class)->findAll();
        foreach($exchangeRates as $exchangeRate) {
            $table[$exchangeRate->getFrom()][$exchangeRate->getTo()] = $exchangeRate->getRate();
        }
        return new ExchangeRatesTable($table);
    }

    public function importRates()
    {
        $currencies = $this->getEnabledCurrencies();

        foreach($currencies as $currencyCode) {
            $rates = $this->fetchRates($currencyCode);
            foreach($rates as $toCode => $rate) {
                $exchangeRate = new CurrencyExchangeRate();
                $exchangeRate->setFrom($currencyCode);
                $exchangeRate->setTo($toCode);
                $exchangeRate->setRate($rate);

                $this->entityManager->persist($exchangeRate);
            }
        }
        $this->entityManager->flush();
    }

    public function getExchangeRatesTable()
    {
        if($this->exchangeRatesTable === null) {
            $this->exchangeRatesTable = $this->createExchangeRatesTable();
        }
        return $this->exchangeRatesTable;
    }

    public function fetchRates($currency)
    {
        $currencies = $this->getEnabledCurrencies();
        $symbols = implode(',', $currencies);
        $url = 'http://api.fixer.io/latest?base='.$currency.'&symbols='.$symbols;

        $start = microtime(true);

        $client = new Client();
        $response = $client->get($url);

        $body = (string)$response->getBody();
        echo microtime(true) - $start;die;
        $result = json_decode($body, true);
        return $result['rates'];
    }

    public function getExchangeRate($fromCurrency, $toCurrency = null)
    {
        $baseCurrency = $this->getBaseCurrency();
        $toCurrency = $toCurrency ?: $baseCurrency;
        if($fromCurrency === $toCurrency) {
            return 1;
        }
        $table = $this->getExchangeRatesTable();
        return $table->getRate($fromCurrency, $toCurrency);
    }
}