<?php

namespace ComBank\Bank;

class InternationalBankAccount extends BankAccount
{
    private string $convertedCurrency = "€"; // Por defecto sin conversión
    private float $convertedBalance;

    public function __construct(float $newBalance = 0.0, string $currency = "€")
    {
        parent::__construct(newBalance: $newBalance, currency: $currency);
        $this->convertedBalance = $newBalance; // Inicialmente sin conversión
    }

    public function getConvertedCurrency(): string
    {
        return $this->convertedCurrency;
    }

    public function setConvertedCurrency(string $currency): void
    {
        $this->convertedCurrency = $currency;
    }

    public function getConvertedBalance(): float
    {
        return $this->convertedBalance;
    }

    public function convertBalance(string $toCurrency, float $conversionRate): void
    {
        $this->convertedCurrency = $toCurrency;
        $this->convertedBalance = $this->balance * $conversionRate;
    }
}

