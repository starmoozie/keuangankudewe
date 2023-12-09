<?php

namespace App\Http\Controllers\Admin\Resources\Income;

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
            ->type('date')
            ->default('2023-11-01');

        $this->crud->column('transactionCategory')
            ->label(__('starmoozie::title.category'));

        $this->crud->column('notes')
            ->label(__('starmoozie::title.note'))
            ->type('textarea');

        $this->crud->column('bank')
            ->label(__('starmoozie::title.bank'));

        $this->crud->column('amount_formatted')
            ->label(__('starmoozie::title.amount'))
            ->wrapper([
                'element' => 'div',
                'class'   => 'text-right'
            ]);
    }
}
