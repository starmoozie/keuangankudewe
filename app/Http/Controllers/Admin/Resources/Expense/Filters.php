<?php

namespace App\Http\Controllers\Admin\Resources\Expense;

use App\Models\TransactionCategory;
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
        $this->creatorFilter();

        $this->dateRangeFilter('dates', 'selectByDatesRange');

        $this->crud->filter('transactioncategory')
            ->label(__('starmoozie::title.category'))
            ->type('select2_multiple')
            ->values(TransactionCategory::pluck('name', 'id')->toArray())
            ->whenActive(fn ($value) => $this->crud->addClause('selectByTransactionCategory', \json_decode($value)))
            ->apply();

        $this->crud->filter('bank')
            ->type('select2')
            ->values(function () {
                return Bank::pluck('name', 'id')->toArray();
            })
            ->whenActive(function ($value) {
                $this->crud->addClause('where', 'bank_id', $value);
            });
    }
}
