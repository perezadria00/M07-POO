<?php

namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use Exception;

trait ApiTrait
{
    use AmountValidationTrait;

    /**
     * Valida una dirección de correo electrónico utilizando la API de Hunter.io.
     *
     * @param string $email
     * @return bool
     * @throws Exception
     */

    
     public function validateEmail(string $email): bool
     {
         // Validación local como respaldo
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             return false;
         }
     
         // Validación con API
         $apiKey = 'ce9781c7b1c88926c4b7bf75c6d519030167b14e';
         $url = "https://api.hunter.io/v2/email-verifier?email=" . urlencode($email) . "&api_key=" . $apiKey;
     
         $response = @file_get_contents($url);
     
         if ($response === false) {
             return true; // Si la API falla, asumimos que es válido basado en la validación local
         }
     
         $data = json_decode($response, true);
     
         return isset($data['data']['result']) && $data['data']['result'] === 'deliverable';
     }
     
     
    

    

    /**
     * Convierte el saldo de EUR a USD.
     *
     * @param float $balance
     * @return float
     * @throws Exception
     */
    function convertedBalance(float $balance): float
    {
        $this->validateAmount($balance);

        $apiKey = "6013b45d685dd41b8e4ed814";
        $baseCurrency = "EUR";
        $targetCurrency = "USD";
        $amount = $balance;

        $url = "https://v6.exchangerate-api.com/v6/$apiKey/pair/$baseCurrency/$targetCurrency/$amount";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($response, true);

        if ($data && isset($data["result"]) && $data["result"] === "success") {
            return (float) $data["conversion_result"];
        } else {
            throw new Exception("Error en la conversión de divisas.");
        }
    }

    /**
     * Valida un número de teléfono utilizando la API de NumVerify.
     *
     * @param string $phoneNumber
     * @return array
     * @throws Exception
     */
    public function verifyPhoneNumber(string $phoneNumber): array
{
    $apiKey = "27ec63146ee1b5adad66dce378b9308f";
    $url = "http://apilayer.net/api/validate?access_key=$apiKey&number=" . urlencode($phoneNumber);

    $response = @file_get_contents($url);

    if ($response === false) {
        return ['valid' => false]; // Si no hay conexión o falla la solicitud
    }

    $data = json_decode($response, true);

    // Si la respuesta contiene el campo 'valid', devuelve los datos
    if (isset($data['valid'])) {
        return $data;
    }

    // Respuesta por defecto si algo sale mal
    return ['valid' => false];
}


    function detectFraud(BankTransactionInterface $transaction) {}
}
