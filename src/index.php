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

// --- [Añadido: Test de cuentas nacionales e internacionales] ---
pl('--------- [Start testing national account (No conversion)] --------');
$bankAccount1 = new BankAccount(newBalance: 500);
pl('My balance: ' . $bankAccount1->getBalance() . ' € (Euro)');

pl('--------- [Start testing international account (Dollar conversion)] --------');
$internationalAccount = new InternationalBankAccount(300);
pl('My balance: ' . $internationalAccount->getBalance() . ' € (Euro)');

try {
    // Usar la API para convertir divisas
    $convertedBalance = $internationalAccount->convertedBalance($internationalAccount->getBalance());
    pl('Converting balance to Dollars (using external API)');
    pl('Converted balance: ' . $convertedBalance . ' $ (USD)');
} catch (Exception $e) {
    pl('Error during conversion: ' . $e->getMessage());
}

// --- [Código Original: Bank Account 1] ---
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    $bankAccount1 = new BankAccount(newBalance: 400);
    $internationalAccount = new InternationalBankAccount(400);

    pl('My InternationalBankAccount balance is: ' . $internationalAccount->getConvertedBalance() . $internationalAccount->getConvertedCurrency());
    pl('My NationalBankAccount balance is: ' . $bankAccount1->getBalance());    

    $bankAccount1->closeAccount();
    pl('My account is now closed.');

    $bankAccount1->reopenAccount();  
    pl('My account is now reopened.');    

    $bankAccount1->transaction(transaction: new DepositTransaction(150));
    pl('My new balance after deposit (+150): ' . $bankAccount1->getBalance() . '$');

    $bankAccount1->transaction(transaction: new WithdrawTransaction(25));
    pl('My new balance after withdrawal (-25): ' . $bankAccount1->getBalance() . '$');

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

pl(""); 

$email = "perezadria00@gmail.com";
$person = new Person(name: "Adrià", idCard: 1, email: $email, phone_number: "+34608337960");


// Validar número de teléfono
$phoneValidationMessage = $person->verifyPhoneNumber($person->getPhoneNumber());
pl($phoneValidationMessage);

// --- [Añadido: Validación de correos electrónicos] ---
pl("\n[Start testing account email address functionality]");
$emailValid = "john.doe@example.com";
$personValid = new Person(name: "John Doe", idCard: 1, email: $emailValid, phone_number: "+34608337960");
pl('Validating email: ' . $emailValid);
if ($personValid->validateEmail($personValid->getEmail())) {
    pl('Email is valid.');
} else {
    pl('Error: Invalid email address: ' . $emailValid);
}

$emailInvalid = "jane.doe@invalid-email";
$personInvalid = new Person(name: "Jane Doe", idCard: 2, email: $emailInvalid, phone_number: "+34608337960");
pl('Validating email: ' . $emailInvalid);
if ($personInvalid->validateEmail($personInvalid->getEmail())) {
    pl('Error: Invalid email address: ' . $emailInvalid);
} else {
    pl('Email is invalid.');
}

// --- [Código Original: Bank Account 2] ---
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    $bankAccount2 = new BankAccount(newBalance: 200);

    $silverOverdraft = new SilverOverdraft();
    $bankAccount2->setOverdraft($silverOverdraft);

    pl('Current balance: ' . $bankAccount2->getBalance() . '$');

    $bankAccount2->transaction(transaction: new DepositTransaction(100));
    pl('Balance after deposit (+100): ' . $bankAccount2->getBalance() . '$');

    $bankAccount2->transaction(transaction: new WithdrawTransaction(300));
    pl('Balance after withdrawal (-300): ' . $bankAccount2->getBalance() . '$');

    $bankAccount2->transaction(transaction: new WithdrawTransaction(50));
    pl('Balance after withdrawal (-50): ' . $bankAccount2->getBalance() . '$');

    $bankAccount2->transaction(transaction: new WithdrawTransaction(120));
} catch (FailedTransactionException $e) {
    pl('Transaction failed: ' . $e->getMessage());
}
pl('Balance after failed transaction: ' . $bankAccount2->getBalance() . '$');

try {
    $bankAccount2->transaction(transaction: new WithdrawTransaction(20));
    pl('Balance after withdrawal (-20): ' . $bankAccount2->getBalance() . '$');

    $bankAccount2->closeAccount();
    pl('Account closed.');

    $bankAccount2->closeAccount();
} catch (BankAccountException $e) {
    pl('Error: ' . $e->getMessage());
}
