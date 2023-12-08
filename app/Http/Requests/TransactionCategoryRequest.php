<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\TransactionCategory;
use App\Constants\TransactionConstant;

class TransactionCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'max:20',
                'required',
                'regex:/^[a-z A-Z]+$/',
                Rule::unique(TransactionCategory::class)
            ],
            'type' => [
                'required',
                Rule::in([TransactionConstant::INCOME, TransactionConstant::EXPENSE])
            ]
        ];
    }
}
