<?php

namespace ComBank\Bank;

class InternationalBankAccount extends BankAccount
{
    public function getConvertedBalance(): float
    {
       
        return $this->convertedBalance($this->getBalance());
    }
}


