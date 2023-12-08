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
            ->label(__('starmoozie::title.income'))
            ->wrapper([
                'element' => 'div',
                'class'   => 'text-right'
            ]);

        $this->crud->column('credit')
            ->label(__('starmoozie::title.expense'))
            ->wrapper([
                'element' => 'div',
                'class'   => 'text-right'
            ]);

        $this->crud->column('')
            ->label(__('starmoozie::title.balance'))
            ->wrapper([
                'element' => 'div',
                'class'   => 'text-right'
            ]);
    }
}
