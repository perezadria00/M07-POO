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
    $apiKey = 'ce9781c7b1c88926c4b7bf75c6d519030167b14e';
    $url = "https://api.hunter.io/v2/email-verifier?email=" . urlencode($email) . "&api_key=" . $apiKey;

    $response = file_get_contents($url);

    if ($response === false) {
        return false; // Error en la solicitud
    }

    // Depuración de la respuesta
    var_dump($response);
    die();

    $data = json_decode($response, true);

    // Verificar que la API devuelva un resultado válido
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
    public function verifyPhoneNumber(string $phoneNumber): string
{
    $apiKey = "27ec63146ee1b5adad66dce378b9308f";
    $url = "http://apilayer.net/api/validate?access_key=$apiKey&number=" . urlencode($phoneNumber);

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['valid']) && $data['valid']) {
        return "Phone number valid: Yes";
    }

    return "Phone number valid: No";
}


    function detectFraud(BankTransactionInterface $transaction) {}
}
