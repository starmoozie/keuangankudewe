<?php

namespace App\Http\Controllers\Admin\Resources\TransactionCategory;

trait Columns
{
    /**
     * Define create / update form fields.
     * 
     * @return void
     */
    protected function setColumns()
    {
        $this->crud->column('name')
            ->label(__('starmoozie::base.name'));

        $this->crud->column('type_alias')
            ->label(__('starmoozie::title.category'));
    }
}
