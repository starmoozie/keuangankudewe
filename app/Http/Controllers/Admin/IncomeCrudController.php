<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction as Model;
use App\Http\Requests\IncomeRequest as Request;
use App\Constants\TransactionConstant;

class IncomeCrudController extends BaseCrudController
{
    const TRANSACTION_CATEGORY = [TransactionConstant::BOTH, TransactionConstant::INCOME];

    use Resources\Income\Main;

    protected $model   = Model::class;
    protected $request = Request::class;
    protected $scopes  = [
        'selectIncome'
    ];
    protected $orders  = [
        ['name' => 'dates', 'type' => 'desc'],
        ['name' => 'created_at', 'type' => 'desc'],
    ];
    protected $exclude_columns = [
        'details',
        'created_by',
        'updated_at',
        'is_income'
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
        $this->addRequest($this->crud->getRequest(), ['created_by' => starmoozie_user()->id, 'is_income' => true]);

        return $this->traitStore();
    }
}
