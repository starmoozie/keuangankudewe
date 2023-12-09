<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Bank;

class CheckAmountNotGreaterThanRule implements Rule
{
    private $type;
    private $is_bank;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type, $is_bank)
    {
        $this->type    = $type;
        $this->is_bank = $is_bank;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->is_bank) {
            return $this->checkBank($value);
        } else {
            return $this->checkWallet($value);
        }
    }

    private function checkBank($value)
    {
        $incomes  = Transaction::selectIncome()->sum('details->amount');
        $expenses = Transaction::selectExpense()->fromBank()->sum('details->amount');

        if (\request()->id) {
            # code...
        } else {
        }
        dd($incomes, $expenses);
    }

    private function checkWallet($value)
    {
        $wallet   = Wallet::sum('amount');
        $expenses = Transaction::selectExpense()->fromWallet()->sum('details->amount');

        if (\request()->id) {
            $current  = Transaction::find(\request()->id);
            $saldo    = $wallet - ($expenses - ($current->amount - \rupiahToNumber($value)));
        } else {
            $saldo    = $wallet - ($expenses + \rupiahToNumber($value));
        }

        return $saldo >= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
