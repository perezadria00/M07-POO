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
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\FailedTransactionException;

class WithdrawTransaction implements BankTransactionInterface
{
    private float $amount;

    public function __construct(float $amount)
    {
        if ($amount <= 0) {
            throw new ZeroAmountException("The withdrawal amount must be greater than zero.");
        }
        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $account): float
    {
        // Get the current balance of the account
        $currentBalance = $account->getBalance();

        // Calculate the new balance after withdrawal
        $newBalance = $currentBalance - $this->amount;

        // Get the configured overdraft and its limit
        $overdraft = $account->getOverdraft();
        $overdraftLimit = $overdraft->getOverdraftFundsAmount();

        // If no overdraft is allowed (overdraft limit is 0) and the balance is negative, throw InvalidOverdraftFundsException
        if ($overdraftLimit === 0.0 && $newBalance < 0) {
            throw new InvalidOverdraftFundsException('Insufficient funds for withdrawal without overdraft.');
        }

        // If the new balance is within the allowed overdraft limit, apply the transaction
        if ($newBalance >= -$overdraftLimit) {
            $account->setBalance($newBalance);
            return $newBalance;
        }

        // If the overdraft limit is exceeded, throw FailedTransactionException
        throw new FailedTransactionException('The withdrawal amount exceeds the allowed overdraft limit.');
    }

    /**
     * Gets the transaction information.
     *
     * @return string
     */
    public function getTransactionInfo(): string
    {
        return "WITHDRAW_TRANSACTION";
    }

    /**
     * Gets the transaction amount.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}

