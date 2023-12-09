<?php

namespace App\Http\Controllers\Admin\Resources\Expense;

use App\Constants\TransactionConstant;

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

        $this->transactionCategoryFilter([TransactionConstant::BOTH, TransactionConstant::EXPENSE]);

        $this->bankFilter();
    }
}
