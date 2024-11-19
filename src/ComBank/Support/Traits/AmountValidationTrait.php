<?php namespace ComBank\Support\Traits;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:35 PM
 */

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

trait AmountValidationTrait
{
    /**
     * Valida que el monto sea positivo y mayor que cero.
     *
     * @param float $amount
     * @throws InvalidArgsException
     * @throws ZeroAmountException
     */
    public function validateAmount(float $amount): void
    {
        if (!is_numeric($amount) || $amount < 0) {
            throw new InvalidArgsException("El monto debe ser un número positivo.");
        }

        if ($amount === 0.0) {
            throw new ZeroAmountException("El monto no puede ser cero.");
        }
    }
}
