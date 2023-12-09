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
        $is_update = $this->method() === "PUT";
        $id        = \request()->id;

        return [
            'name' => [
                'max:20',
                'required',
                'regex:/^[a-z A-Z]+$/',
                Rule::unique(TransactionCategory::class)->when($is_update, fn ($q) => $q->ignore($id))
            ],
            'type' => [
                'required',
                Rule::in(\array_column(TransactionConstant::ALL, 'value'))
            ]
        ];
    }
}
