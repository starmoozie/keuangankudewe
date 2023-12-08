<?php

namespace App\Http\Controllers\Admin\Resources\Report;

trait Columns
{
    /**
     * Define create / update form fields.
     * 
     * @return void
     */
    protected function setColumns()
    {
        $this->crud->column('dates')
        ->label(__('starmoozie::title.date'))
        ->type('date');

        $this->crud->column('bank')
        ->label(__('starmoozie::title.bank'));

        $this->crud->column('debit')
            ->label(__('starmoozie::title.income'));

        $this->crud->column('credit')
            ->label(__('starmoozie::title.expense'));

        $this->crud->column('')
            ->label(__('starmoozie::title.balance'));
    }
}
