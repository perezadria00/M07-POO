<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

class NoOverdraft implements OverdraftInterface 
{
 

   

    public function isGrantOverdraftFunds(float $newAmount): bool {
        return ($this->getOverdraftFundsAmount() + $newAmount) >= 0;
    }

    public function getOverdraftFundsAmount(): float {
        
    }
}

