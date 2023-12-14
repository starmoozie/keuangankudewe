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
            ->type('date');

        $this->crud->column('transactionCategory')
            ->label(__('starmoozie::title.category'))
            ->orderable(true)
            ->orderLogic(
                fn ($query, $crud_column, $direction) => $query->orderByRelationship(['name' => 'transactionCategory', 'column' => 'name'], $direction)
            );

        $this->crud->column('notes')
            ->label(__('starmoozie::title.note'))
            ->type('textarea');

        $this->crud->column('bank')
            ->label(__('starmoozie::title.bank'))
            ->orderable(true)
            ->orderLogic(
                fn ($query, $crud_column, $direction) => $query->orderByRelationship(['name' => 'bank', 'column' => 'name'], $direction)
            );

        $this->crud->column('amount_formatted')
            ->label(__('starmoozie::title.amount'))
            ->wrapper([
                'element' => 'div',
                'class'   => 'text-right'
            ])
            ->orderable(true)
            ->orderLogic(
                fn ($query, $crud_column, $direction) => $query->orderByAmount($direction)
            );
    }
}
