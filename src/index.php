<?php

use ComBank\Bank\BankAccount;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidOverdraftFundsException; // Asegúrate de incluir esta excepción
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

require_once 'bootstrap.php';

//---[Bank account 1]---/
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // Crear una nueva cuenta con saldo 400
    $bankAccount1 = new BankAccount(newBalance: 400);
    pl('My balance: ' . $bankAccount1->getBalance() . '$');

    // Cerrar cuenta
    pl('My account is now closed. ' . $bankAccount1->closeAccount());

    // Reabrir cuenta
    pl('My account is now open. ' . $bankAccount1->reopenAccount());

    // Depósito +150
    pl('Doing transaction deposit (+150) with current balance: ' . $bankAccount1->getBalance() . '$');
    $bankAccount1->transaction(transaction: new DepositTransaction(150));
    pl('My new balance after deposit (+150): ' . $bankAccount1->getBalance() . '$');

    // Retiro -25
    pl('Doing transaction withdrawal (-25) with current balance: ' . $bankAccount1->getBalance() . '$');
    $bankAccount1->transaction(transaction: new WithdrawTransaction(25));
    pl('My new balance after withdrawal (-25): ' . $bankAccount1->getBalance() . '$');

    // Intentar retirar -600
    pl('Doing transaction withdrawal (-600) with current balance: ' . $bankAccount1->getBalance() . '$');
    $bankAccount1->transaction(transaction: new WithdrawTransaction(600)); 
    
    
   
    
    
} catch (InvalidOverdraftFundsException $e) {  
    pl('Error transaction: ' . $e->getMessage());
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}

pl('My balance after failed last transaction: ' . $bankAccount1->getBalance() . '$');

pl('My account is now closed.' . $bankAccount1->closeAccount());



//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {

    // show balance account

    $bankAccount2 = new BankAccount(newBalance:'200');

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
   
   

    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());

    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());

    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

try {
} catch (BankAccountException $e) {
    pl($e->getMessage());
}
