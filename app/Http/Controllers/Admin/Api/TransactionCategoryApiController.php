<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\TransactionCategory as Model;

class TransactionCategoryApiController extends BaseApiController
{
    protected $model  = Model::class;
    protected $column = "name";
}
