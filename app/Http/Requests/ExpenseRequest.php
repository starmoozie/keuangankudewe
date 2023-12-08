<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\Bank;

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
            'bank' => [
                'required',
                Rule::exists(Bank::class, 'id')
            ],
            'amount' => [
                'required',
                'max:15'
            ],
            'dates' => [
                'required',
                'date'
            ]
        ];
    }
}
