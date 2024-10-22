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
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{
    
    
    private float $balance;
    private string $status;
    private OverdraftInterface $overdraft;

    
    public function __construct(float $newBalance = 0.0)
    {
       $this->setBalance(balance: $newBalance);
       $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Set the value of balance
     *
     * @return  self
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
     * @return  self
     */ 
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }

    /**
     * Set the value of overdraft
     *
     * @return  self
     */ 
    public function setOverdraft(OverdraftInterface $overdraft): self
    {
        $this->overdraft = $overdraft;
        return $this;
    }

    public function transaction(BankTransactionInterface $transaction): void{
        $amount = $transaction->getAmount();
        $this->setBalance($this->getBalance() + $amount);

    }

    public function openAccount(): void{
        $this->status = BankAccountInterface::STATUS_OPEN;
    }
    public function closeAccount(): void{

        $this->status = BankAccountInterface::STATUS_CLOSED;
        
    }

    public function reopenAccount(): void{
        
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function isOpen(): bool{
        if($this->status = BankAccountInterface::STATUS_OPEN){
            return true;
        }else{
            return false;
        }
    }


    public function applyOverdraft(OverdraftInterface $overdraft): void{

    }

    

  
}



