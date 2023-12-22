<?php

namespace App\Http\Controllers\Admin\Resources\Expense;

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

        $this->transactionCategoryFilter(Self::TRANSACTION_CATEGORY);

        $this->bankFilter();

        $this->crud->filter('used_for')
            ->label(__('starmoozie::title.used_for'))
            ->type('select2_multiple')
            ->values(fn () => $this->usedFor())
            ->whenActive(fn ($values) => $this->crud->addClause('whereInJson', 'details', \json_decode($values)));
    }
}
