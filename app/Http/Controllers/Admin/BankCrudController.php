<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank as Model;
use App\Http\Requests\BankRequest as Request;

class BankCrudController extends BaseCrudController
{
    use Resources\Bank\Main;

    protected $model   = Model::class;
    protected $request = Request::class;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        parent::setup();

        $this->crud->addClause('withSum', 'transactionIncomes as incomes', 'amount');
        $this->crud->addClause('withSum', 'transactionExpenses as expenses', 'amount');
    }
}
