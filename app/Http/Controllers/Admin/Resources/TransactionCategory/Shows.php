<?php

namespace App\Http\Controllers\Admin\Resources\TransactionCategory;

trait Shows
{
    /**
     * Define create / update form fields.
     * 
     * @return void
     */
    protected function setShows()
    {
        $this->setColumns();
    }
}
