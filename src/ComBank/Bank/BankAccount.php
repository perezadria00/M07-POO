<?php

namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Bank\Contracts\CurrencyConverterInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Bank\Person;
use ComBank\Support\Traits\ApiTrait;

class BankAccount implements BankAccountInterface
{

    use ApiTrait;
    protected float $balance;
    protected string $status;
    protected OverdraftInterface $overdraft;

    protected string $currency;

    protected Person $holder;



    public function __construct(float $newBalance = 0.0)
    {
        $this->setBalance(balance: $newBalance);
        $this->status = BankAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft();
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Set the value of balance
     *
     * @return self
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getOverdraft(): OverdraftInterface
    {
        if (!isset($this->overdraft)) {
            throw new \Exception("Overdraft not initialized");
        }
        return $this->overdraft;
    }

    /**
     * Set the value of overdraft
     *
     * @return self
     */
    public function setOverdraft(OverdraftInterface $overdraft): self
    {
        $this->overdraft = $overdraft;
        return $this;
    }

    public function transaction(BankTransactionInterface $transaction): void
    {
        if ($this->status === BankAccountInterface::STATUS_CLOSED) {
            throw new BankAccountException("Transactions cannot be performed on a closed account.");
        }

        // If the account is open, apply the transaction
        $newBalance = $transaction->applyTransaction($this);
        $this->setBalance($newBalance);
    }

    public function openAccount(): void
    {
        if ($this->status === BankAccountInterface::STATUS_OPEN) {
            throw new BankAccountException("The account is already open.");
        }
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function closeAccount(): void
    {
        if ($this->status === BankAccountInterface::STATUS_CLOSED) {
            throw new BankAccountException("The account is already closed.");
        }
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function reopenAccount(): void
    {
        if ($this->status === BankAccountInterface::STATUS_OPEN) {
            throw new BankAccountException("The account is already open.");
        }
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function isOpen(): bool
    {
        return $this->status == BankAccountInterface::STATUS_OPEN;
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }

   
}
