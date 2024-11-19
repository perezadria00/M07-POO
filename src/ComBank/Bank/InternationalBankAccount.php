<?php

namespace ComBank\Bank;

class InternationalBankAccount extends BankAccount
{
    public function getConvertedBalance(): float
    {
       
        return $this->convertedBalance($this->getBalance());
    }   

    public function getConvertedCurrency() : string{

        $CURRENCY = '$';

        return $CURRENCY;
    }
}


