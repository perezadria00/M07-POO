<?php

namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\ZeroAmountException;

class DepositTransaction implements BankTransactionInterface
{
    private float $amount; // Transaction amount

    public function __construct(float $amount) {
        if ($amount <= 0) {
            throw new ZeroAmountException('The deposit must be greater than 0.');
        }
        $this->amount = $amount;
    }

    /**
     * Applies the deposit transaction to the bank account.
     *
     * @param BankAccountInterface $account
     * @return float New balance after the deposit.
     */
    public function applyTransaction(BankAccountInterface $account): float {
        // Get the current balance of the account
        $currentBalance = $account->getBalance();
        
        // Calculate the new balance
        $newBalance = $currentBalance + $this->amount;
        
        // Set the new balance in the account
        $account->setBalance($newBalance);
        
        // Return the new balance
        return $newBalance;
    }

    /**
     * Gets the transaction information.
     *
     * @return string
     */
    public function getTransactionInfo(): string {
        return "DEPOSIT_TRANSACTION";
    }

    /**
     * Gets the transaction amount.
     *
     * @return float
     */
    public function getAmount(): float {
        return $this->amount;
    }
}


