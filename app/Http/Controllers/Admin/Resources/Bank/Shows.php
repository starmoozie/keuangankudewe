<?php

namespace App\Http\Controllers\Admin\Resources\Bank;

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
