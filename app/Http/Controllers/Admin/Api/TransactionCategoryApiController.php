<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\TransactionCategory as Model;
use Illuminate\Http\Request;

class TransactionCategoryApiController extends BaseApiController
{
    protected $model  = Model::class;
    protected $column = "name";

    /**
     * Filter list operations
     */
    public function filter(Request $request)
    {
        return (new $this->model)
            ->whereIn('type', \json_decode($request->rules))
            ->when($request->term, fn ($q) => $q->where($this->column, "LIKE", "%{$request->term}%"))
            ->orderBy($this->column)
            ->pluck($this->column, 'id');
    }
}
