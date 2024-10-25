<?php

namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\ZeroAmountException;

class DepositTransaction implements BankTransactionInterface
{
    private float $amount; // Monto de la transacción

    public function __construct(float $amount) {
        if ($amount <= 0) {
            throw new ZeroAmountException('El depósito debe ser superior a 0.');
        }
        $this->amount = $amount;
    }

    /**
     * Aplica la transacción de depósito a la cuenta bancaria.
     *
     * @param BankAccountInterface $account
     * @return float Nuevo saldo después del depósito.
     */
    public function applyTransaction(BankAccountInterface $account): float {
        // Obtener el saldo actual de la cuenta
        $currentBalance = $account->getBalance();
        
        // Calcular el nuevo saldo
        $newBalance = $currentBalance + $this->amount;
        
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

