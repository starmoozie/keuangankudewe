<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank as Model;
use App\Http\Requests\BankRequest as Request;

class BankCrudController extends BaseCrudController
{
    use Resources\Bank\Main;

    protected $model   = Model::class;
    protected $request = Request::class;
}
