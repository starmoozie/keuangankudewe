<?php

namespace App\Http\Controllers\Admin\Resources\Income;

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
