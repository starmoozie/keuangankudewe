<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction as Model;
use App\Http\Requests\ReportRequest as Request;

class ReportCrudController extends BaseCrudController
{
    use Resources\Report\Main;

    protected $model   = Model::class;
    protected $request = Request::class;
    protected $orders  = [
        ['name' => 'dates', 'type' => 'asc'],
        ['name' => 'created_at', 'type' => 'asc'],
    ];
    protected $exclude_columns = [
        'notes',
        'details',
        'created_by',
        'updated_at',
        'transaction_category_id',
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
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        parent::setupListOperation();

        $this->crud->setOperationSetting('searchableTable', false);

        $this->crud->removeAllButtons();
        $this->crud->denyAccess(['edit', 'create', 'delete']);
    }
}
