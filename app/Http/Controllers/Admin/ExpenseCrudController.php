<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction as Model;
use App\Http\Requests\ExpenseRequest as Request;

class ExpenseCrudController extends BaseCrudController
{
    use Resources\Expense\Main;

    protected $model   = Model::class;
    protected $request = Request::class;
    protected $scopes  = [
        'selectExpense'
    ];
    protected $orders  = [
        ['name' => 'dates', 'type' => 'desc'],
        ['name' => 'created_at', 'type' => 'desc'],
    ];

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        parent::setup();
        if (!\request()->has('dates')) {
            $this->crud->addClause('selectCurrentMonth');
        }
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->addRequest($this->crud->getRequest(), ['created_by' => starmoozie_user()->id]);

        return $this->traitStore();
    }
}
