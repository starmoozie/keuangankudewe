<?php

namespace App\Http\Controllers\Admin;

use App\Models\TransactionCategory as Model;
use App\Http\Requests\TransactionCategoryRequest as Request;

class TransactionCategoryCrudController extends BaseCrudController
{
    use Resources\TransactionCategory\Main;

    protected $model   = Model::class;
    protected $request = Request::class;
}
