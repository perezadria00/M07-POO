<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class WithdrawTransaction implements BankTransactionInterface

{
    private float $amount; // Monto de la transacción

    public function __construct(float $amount)
    {

        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $account): float
{
    // Obtener el saldo actual de la cuenta
    $currentBalance = $account->getBalance();

    // Calcular el nuevo saldo
    $newBalance = $currentBalance - $this->amount;

    // Verificar que el nuevo saldo no sea negativo
    if ($newBalance < 0) {
        throw new InvalidOverdraftFundsException('Insufficient funds for withdrawal.');
    }

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
    public function getTransactionInfo(): string
    {
        return "Depósito de" . $this->amount . "$";
    }

    /**
     * Obtiene el monto de la transacción.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
