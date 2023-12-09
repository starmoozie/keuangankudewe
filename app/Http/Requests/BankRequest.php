<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\Bank;

class BankRequest extends BaseRequest
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
                'max:10',
                'required',
                'regex:/^[a-z A-Z]+$/',
                Rule::unique(Bank::class)->when($is_update, fn ($q) => $q->ignore($id))
            ]
        ];
    }
}
