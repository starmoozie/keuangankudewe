<?php

namespace App\Constants;

class TransactionConstant
{
    const EXPENSE  = 0;
    const INCOME   = 1;

    const ALL      = [
        ['label' => 'expense', 'value' => Self::EXPENSE, 'color' => 'warning'],
        ['label' => 'income', 'value' => Self::INCOME, 'color' => 'success'],
    ];
}
