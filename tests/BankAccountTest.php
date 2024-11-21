<?php

use PHPUnit\Framework\TestCase;
use ComBank\Bank\BankAccount;
use ComBank\Bank\NationalBankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Bank\Person;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;

class BankAccountTest extends TestCase
{
    // Test that a new BankAccount is initialized with the correct balance
    public function testInitialBalanceIsSetCorrectly(): void
    {
        $account = new BankAccount(100.0);
        $this->assertEqualsWithDelta(100.0, $account->getBalance(), 0.001);
    }

    // Test that a deposit transaction updates the balance correctly
    public function testDepositTransaction(): void
    {
        $bankAccount = new BankAccount(200.0);
        $bankAccount->transaction(new DepositTransaction(30.0));
        $this->assertEqualsWithDelta(230.0, $bankAccount->getBalance(), 0.001);
    }

    // Test that a withdrawal transaction updates the balance correctly
    public function testWithdrawTransaction(): void
    {
        $bankAccount = new BankAccount(200.0);
        $bankAccount->transaction(new WithdrawTransaction(150.0));
        $this->assertEqualsWithDelta(50.0, $bankAccount->getBalance(), 0.001);
    }

    // Test that a BankAccount cannot be reopened when already open
    public function testCannotReopenOpenAccount(): void
    {
        $this->expectException(BankAccountException::class);

        $account = new BankAccount(100.0);
        $account->reopenAccount();  // should throw an exception because the account is already open
    }

    // Test closing and reopening the bank account
    public function testCanCloseAndReopenAccount(): void
    {
        $account = new BankAccount(100.0);
        $account->closeAccount();
        $this->assertFalse($account->isOpen());

        $account->reopenAccount();
        $this->assertTrue($account->isOpen());
    }

    // Test overdraft application with a mock OverdraftInterface
    public function testWithdrawWithOverdraft(): void
    {
        $bankAccount = new BankAccount(250.0);
        $bankAccount->applyOverdraft(new SilverOverdraft());
        $bankAccount->transaction(new WithdrawTransaction(300.0));

        $this->assertEqualsWithDelta(-50.0, $bankAccount->getBalance(), 0.001);
    }

    // Test a failed transaction due to overdraft refusal
    public function testFailedTransactionWithOverdraft(): void
    {
        $this->expectException(FailedTransactionException::class);

        $bankAccount = new BankAccount(100.0);
        $bankAccount->applyOverdraft(new SilverOverdraft());
        $bankAccount->transaction(new WithdrawTransaction(201.0)); // should fail
    }

    // Test closing an account and performing a transaction after that, which should fail
    public function testTransactionAfterAccountClosed(): void
    {
        $this->expectException(BankAccountException::class);

        $bankAccount = new BankAccount(100.0);
        $bankAccount->closeAccount();

        $bankAccount->transaction(new DepositTransaction(50.0)); // Should throw exception because the account is closed
    }

    public function testNationalBankAccountCurrency(): void
{
    // Crear una cuenta bancaria nacional con la moneda predeterminada
    $bankAccount = new NationalBankAccount(newBalance: 400);

    // Verificar que la moneda es "€"
    $this->assertEquals("€", $bankAccount->getCurrency());
}


public function testInternationalBankAccountCurrencyWithoutConversion(): void
{
    // Crear una cuenta internacional
    $internationalAccount = new InternationalBankAccount(newBalance: 400);

    // Verificar moneda inicial y balance sin conversión
    $this->assertEquals("€", $internationalAccount->getConvertedCurrency());
    $this->assertEquals(400, $internationalAccount->getConvertedBalance());
}

public function testInternationalBankAccountCurrencyWithConversion(): void
{
    // Crear una cuenta internacional
    $internationalAccount = new InternationalBankAccount(newBalance: 400);

    // Simular una conversión de moneda
    $internationalAccount->convertBalance(toCurrency: "$", conversionRate: 1.1);

    // Verificar moneda convertida y balance convertido
    $this->assertEquals("$", $internationalAccount->getConvertedCurrency());
    $this->assertEqualsWithDelta(440.0, $internationalAccount->getConvertedBalance(), 0.0001);
}


public function testValidEmailForAccountHolder(): void
{
    $person = new Person(name: "Adrià", idCard: 1, email: "perezadria00@gmail.com", phone_number: "+34608337960");

    // Verifica que el correo es válido
    $this->assertTrue($person->validateEmail($person->getEmail()));
}




    

    // Test invalid email for account holder
    public function testInvalidEmailForAccountHolder(): void
    {
        $person = new Person("Adrià", 1, "invalid.email", "+34608337960");
        $this->assertFalse($person->validateEmail($person->getEmail()));
    }

    public function testNewFreeFunctionality(): void
    {
        $person = new Person(name: "Adrià", idCard: 1, email: "valid.email@example.com", phone_number: "+34608337960");
    
        // Llama a verifyPhoneNumber y obtiene el resultado como array
        $phoneValidationResult = $person->verifyPhoneNumber(phoneNumber: $person->getPhoneNumber());
    
        // Construye un mensaje basado en el resultado
        $phoneValidationMessage = $phoneValidationResult['valid'] ? "Phone number valid: Yes" : "Phone number valid: No";
    
        // Verifica que el mensaje contiene el texto esperado
        $this->assertStringContainsString("Phone number valid: Yes", $phoneValidationMessage);
    }
    
    
    
}