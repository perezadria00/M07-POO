<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction 

{
    private float $amount; // Monto de la transacción

    public function __construct(float $amount) {
        
        $this->amount = $amount;
    }

    /**
     * Aplica la transacción de depósito a la cuenta bancaria.
     *
     * @param BackAccountInterface $account
     * @return float Nuevo saldo después del depósito.
     */
    public function applyTransaction(BackAccountInterface $account): float {
        // Obtener el saldo actual de la cuenta
        $currentBalance = $account->getBalance();
        
        // Calcular el nuevo saldo
        $newBalance = $currentBalance - $this->amount;
        
        // Establecer el nuevo saldo en la cuenta
        $account->setBalance($newBalance);
        
        // Devolver el nuevo saldo
        return $newBalance;
    }

    /**
     * Obtiene la información de la transacción.
     *
     * @return string
     */
    public function getTransactionInfo(): string {
        return "Depósito de" . $this->amount . "$";
    }

    /**
     * Obtiene el monto de la transacción.
     *
     * @return float
     */
    public function getAmount(): float {
        return $this->amount;
    }
  
}
