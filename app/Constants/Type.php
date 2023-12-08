<?php

namespace App\Constants;

class Type
{
    const BANK   = 0;
    const WALLET = 1;

    const ALL  = [
        Self::BANK   => 'Bank',
        Self::WALLET => 'Wallet',
    ];
}
