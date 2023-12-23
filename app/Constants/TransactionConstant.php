<?php

namespace App\Constants;

class TransactionConstant
{
    const EXPENSE  = 0;
    const INCOME   = 1;
    const BOTH     = 2;
    const BALANCE  = 3;

    const ALL      = [
        ['label' => 'expense', 'value' => Self::EXPENSE, 'color' => 'warning'],
        ['label' => 'income', 'value' => Self::INCOME, 'color' => 'success'],
        ['label' => 'both', 'value' => Self::BOTH, 'color' => 'danger'],
    ];

    const DASHBOARD = [
        ['label' => 'income', 'value' => Self::INCOME, 'color' => 'success', 'endpoint' => 'income'],
        ['label' => 'expense', 'value' => Self::EXPENSE, 'color' => 'warning', 'endpoint' => 'expense'],
        ['label' => 'balance', 'value' => Self::BALANCE, 'color' => 'primary', 'endpoint' => 'report'],
    ];

    const FOR_AYAH  = 0;
    const FOR_BUNDA = 1;
    const FOR_AQILA = 2;
    const FOR_ALL   = 3;

    const USED_FOR  = [
        ['label' => 'ayah', 'value' => Self::FOR_AYAH, 'color' => 'warning'],
        ['label' => 'bunda', 'value' => Self::FOR_BUNDA, 'color' => 'success'],
        ['label' => 'aqila', 'value' => Self::FOR_AQILA, 'color' => 'danger'],
        ['label' => 'all', 'value' => Self::FOR_ALL, 'color' => 'danger'],
    ];
}
