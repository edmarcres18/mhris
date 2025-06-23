<?php

namespace App\Enums;

enum LoanType: string
{
    case HOUSING = 'housing';
    case MULTI_PURPOSE = 'multi_purpose';
    case CALAMITY = 'calamity';

    public function getDefaultInterestRate(): float
    {
        return match($this) {
            self::HOUSING => $this->getHousingLoanInterestRate(),
            self::MULTI_PURPOSE => 10.5,
            self::CALAMITY => 5.95,
        };
    }

    private function getHousingLoanInterestRate(): float
    {
        return 8.125; // Default to the highest rate (30 years)
    }
}
