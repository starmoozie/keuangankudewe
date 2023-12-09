<?php

namespace App\Http\Controllers\Admin\Resources\Bank;

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

        $this->crud->column('balance')
            ->label(__('starmoozie::title.balance'))
            ->wrapper([
                'element' => 'div',
                'class'   => 'text-right'
            ]);
    }
}
