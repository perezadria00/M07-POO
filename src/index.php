<?php

use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidOverdraftFundsException; 
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Bank\Person;

require_once 'bootstrap.php';
require_once 'ComBank/Bank/Person.php';
require_once 'ComBank/Support/Traits/ApiTrait.php';


//---[Bank account 1]---/
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // Crear una nueva cuenta con saldo 400
    $bankAccount1 = new BankAccount(newBalance: 400);
    $internationalAccount = new InternationalBankAccount(400);

    pl('My InternationalBankAccount balance is: ' . $internationalAccount->getConvertedBalance() . $internationalAccount->getConvertedCurrency());
    pl('My NationalBankAccount balance is: ' . $bankAccount1->getBalance());    

    // Cerrar cuenta
    $bankAccount1->closeAccount();
    pl('My account is now closed.');

    // Reabrir cuenta
    $bankAccount1->reopenAccount();  
    pl('My account is now reopened.');    

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

//--- Verificar el correo electrónico ---
$person1 = new Person("Adrià", 1, "adria@gmail.com", +34608337960);
$emailValid = $person1->validateEmail($person1->getEmail());
pl('Email valid: ' . ($emailValid ? 'Yes' : 'No'));

// Validar número de teléfono
$phoneValidationMessage = $person1->verifyPhoneNumber($person1->getPhoneNumber());
pl($phoneValidationMessage);


//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    // Crear una nueva cuenta con saldo 200
    $bankAccount2 = new BankAccount(newBalance: 200);

    // Asignar el sobregiro Silver
    $silverOverdraft = new SilverOverdraft();
    $bankAccount2->setOverdraft($silverOverdraft);

    // Mostrar balance inicial
    pl('Current balance: ' . $bankAccount2->getBalance() . '$');

    // Depósito +100
    pl('Performing deposit (+100) with current balance: ' . $bankAccount2->getBalance() . '$');
    $bankAccount2->transaction(transaction: new DepositTransaction(100));
    pl('Balance after deposit (+100): ' . $bankAccount2->getBalance() . '$');

    // Retiro -300 (deja el balance en 0.0)
    pl('Performing withdrawal (-300) with current balance: ' . $bankAccount2->getBalance() . '$');
    $bankAccount2->transaction(transaction: new WithdrawTransaction(300));
    pl('Balance after withdrawal (-300): ' . $bankAccount2->getBalance() . '$');

    // Retiro -50 (balance debería ser -50.0, permitido por el sobregiro)
    pl('Performing withdrawal (-50) with current balance: ' . $bankAccount2->getBalance() . '$');
    $bankAccount2->transaction(transaction: new WithdrawTransaction(50));
    pl('Balance after withdrawal (-50): ' . $bankAccount2->getBalance() . '$');

    // Intentar retirar -120 (debería fallar porque excede el límite del sobregiro)
    pl('Attempting withdrawal (-120) with current balance: ' . $bankAccount2->getBalance() . '$');
    $bankAccount2->transaction(transaction: new WithdrawTransaction(120));
} catch (FailedTransactionException $e) {
    pl('Transaction failed: ' . $e->getMessage());
}
pl('Balance after failed transaction: ' . $bankAccount2->getBalance() . '$');

try {
    // Retiro -20 (debería ser permitido, deja el balance en -70.0)
    pl('Performing withdrawal (-20) with current balance: ' . $bankAccount2->getBalance() . '$');
    $bankAccount2->transaction(transaction: new WithdrawTransaction(20));
    pl('Balance after withdrawal (-20): ' . $bankAccount2->getBalance() . '$');

    // Cerrar la cuenta
    pl('Closing account...');
    $bankAccount2->closeAccount();
    pl('Account closed.');

    // Intentar cerrar la cuenta de nuevo (debería fallar)
    pl('Attempting to close the account again...');
    $bankAccount2->closeAccount();
} catch (BankAccountException $e) {
    pl('Error: ' . $e->getMessage());
}
