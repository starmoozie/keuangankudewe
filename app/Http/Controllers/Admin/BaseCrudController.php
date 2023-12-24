<?php

namespace App\Http\Controllers\Admin;

use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BaseCrudController extends CrudController
{
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Starmoozie\LaravelMenuPermission\app\Traits\CheckPermission;

    protected $orders  = [];
    protected $scopes  = [];
    protected $select_columns  = [];
    protected $exclude_columns = [];

    public function __construct()
    {
        parent::__construct();

        $this->middleware('doNotCacheResponse')->only(['edit']);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $path    = request()->segment(2);
        $heading = str_replace('-', ' ', $path);
        $label   = __("starmoozie::title.$heading");

        CRUD::setModel($this->model);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . "/$path");
        CRUD::setEntityNameStrings($label, $label);

        // If has order then, loop order
        foreach ($this->orders as $order) {
            CRUD::orderBy($order['name'], $order['type']);
        }

        // If has scopes
        foreach ($this->scopes as $scope) {
            CRUD::addClause($scope);
        }

        $this->selectColumns($this->getListingModelColumns());
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        $this->checkPermission();

        $this->setFilters();

        $this->setColumns();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->checkPermission();

        CRUD::setValidation($this->request);

        $this->setFields();
    }

    /**
     * Define what happens when the Show operation is loaded.
     * 
     * @return void
     */
    protected function setupShowOperation()
    {
        $this->setShows();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());
        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('starmoozie::crud.edit') . ' ' . $this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/starmoozie/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define list columns.
     * 
     * @return void
     */
    protected function setColumns()
    {
        CRUD::setFromDB();
    }

    /**
     * Define create / update form fields.
     * 
     * @return void
     */
    protected function setFields()
    {
        $this->setColumns();
    }

    /**
     * Define show columns.
     * 
     * @return void
     */
    protected function setShows()
    {
        $this->setColumns();
    }

    /**
     * Define filter fields.
     * 
     * @return void
     */
    protected function setFilters()
    {
        //
    }

    /**
     * Define selected columns.
     */
    private function selectColumns($columns): void
    {
        // Select columns query
        CRUD::addClause('select', $columns);
    }

    private function getListingModelColumns(): array
    {
        // If set excluse columns
        if (count($this->exclude_columns)) {
            $columns = collect((new $this->model)->getFillable())
                ->filter(fn ($item) => !in_array($item, $this->exclude_columns))
                ->toArray();
        } elseif (count($this->select_columns)) {
            $columns = $this->select_columns;
        } else {
            $columns = $this->crud->model->getFillable();
        }

        return [...$columns, ...['id']];
    }
}
