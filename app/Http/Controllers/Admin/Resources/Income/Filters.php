<?php

namespace App\Http\Controllers\Admin\Resources\Income;

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

        $this->transactionCategoryFilter(Self::TRANSACTION_CATEGORY);

        $this->bankFilter();
    }
}
