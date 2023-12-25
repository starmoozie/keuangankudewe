<?php

namespace App\Http\Controllers\Admin\Resources\Mutation;

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
            ->label(__('starmoozie::title.date'));

        $this->crud->column('from')
            ->label(__('starmoozie::title.from'));

        $this->crud->column('to')
            ->label(__('starmoozie::title.to'));

        $this->crud->column('amount_formatted')
            ->label(__('starmoozie::title.amount'))
            ->wrapper([
                'element' => 'div',
                'class'   => 'text-right'
            ])
            ->searchLogic(false)
            ->orderable(false);

        $this->crud->column('notes')
            ->label(__('starmoozie::title.note'));
    }
}
