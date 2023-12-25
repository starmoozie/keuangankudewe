<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\Bank;

class MutationRequest extends BaseRequest
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
            'amount' => [
                'max:15'
            ],
            'from' => [
                'required',
                Rule::exists(Bank::class, 'id'),
            ],
            'to' => [
                'required',
                Rule::exists(Bank::class, 'id'),
            ],
            [
                'notes' => 'nullable'
            ]
        ];
    }
}
