<?php

namespace ComBank\Bank\Contracts;

interface CurrencyConverterInterface
{
    public function getExchangeRate(string $fromCurrency, string $toCurrency): float;

    public function convertAmount(float $amount, float $rate): float;
}
