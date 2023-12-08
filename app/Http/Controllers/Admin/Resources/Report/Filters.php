<?php

namespace App\Http\Controllers\Admin\Resources\Report;

use App\Models\Bank;

trait Filters
{
    use \App\Http\Controllers\Admin\Resources\GlobalFilters;

    /**
     * Define filter fields.
     * 
     * @return void
     */
    protected function setFilters()
    {
        $this->dateRangeFilter('dates', 'selectByDatesRange');

        $this->crud->filter('bank')
            ->type('select2')
            ->values(function () {
                return Bank::pluck('name', 'id')->toArray();
            })
            ->whenActive(function ($value) {
                $this->crud->addClause('where', 'bank_id', $value);
            });

        $this->crud->filter('type')
            ->type('select2')
            ->values(function () {
                return [
                    0 => 'Expenses',
                    1 => 'Incomes'
                ];
            })
            ->whenActive(function ($value) {
                $this->crud->addClause('where', 'is_income', $value);
            });
    }
}
