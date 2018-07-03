<?php
namespace AppBundle\Currency;


class CurrencyConverter
{
    /**
     * @var CurrencyRegistry
     */
    private $currencyRegistry;

    /**
     * CurrencyConverter constructor.
     * @param CurrencyRegistry $currencyRegistry
     */
    public function __construct(CurrencyRegistry $currencyRegistry)
    {
        $this->currencyRegistry = $currencyRegistry;
    }

    public function convertCurrency($amount, $fromCurrency, $toCurrency = null)
    {
        $baseCurrency = $this->currencyRegistry->getBaseCurrency();
        $toCurrency = $toCurrency ?: $baseCurrency;

        $rate = $this->currencyRegistry->getExchangeRate($fromCurrency, $toCurrency);

        $converted = $amount * $rate;

        return $converted;
    }
}