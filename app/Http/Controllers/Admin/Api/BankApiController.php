<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Bank as Model;

class BankApiController extends BaseApiController
{
    protected $model  = Model::class;
    protected $column = "name";
}
