<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\{
    TransactionCategory,
    Bank,
};

class ExpenseRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dates' => [
                'required',
                'date'
            ],
            'transactionCategory' => [
                'required',
                Rule::exists(TransactionCategory::class, 'id'),
            ],
            'amount' => [
                'required',
                'max:15'
            ],
            'bank' => [
                'required',
                Rule::exists(Bank::class, 'id'),
            ],
            'notes' => [
                'required',
            ]
        ];
    }
}
