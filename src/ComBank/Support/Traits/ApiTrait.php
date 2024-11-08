<?php

namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTrait{
    
    function validateEmail(string $email){}

    function convertedBalance(float $balance): float{

        $conversion = 1.10;

        $balanceConverted = $balance * $conversion; 

        return $balanceConverted;

    }

    function detectFraud(BankTransactionInterface $transaction){}
}