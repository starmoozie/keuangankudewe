<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Models\TransactionCategory;

trait GlobalFilters
{
    /**
     * Fiter by date range
     */
    protected function dateRangeFilter($column, $scope): void
    {
        $this->crud->filter($column)
            ->label(__('starmoozie::title.date'))
            ->type('date_range')
            ->whenActive(fn ($q) => $this->crud->addClause($scope, json_decode($q)));
    }

    /**
     * Fiter by user creator
     */
    protected function creatorFilter(): void
    {
        if (!$this->crud->hasAccess('personal')) {
            $this->crud->filter('created_by')
                ->label(__('starmoozie::title.creator'))
                ->type('select2_ajax')
                ->values(starmoozie_url('filter/user'))
                ->minimum_input_length(0)
                ->whenActive(fn ($value) => $this->crud->addClause('selectByCreator', $value))
                ->apply();
        }
    }

    /**
     * Fiter by bank
     */
    protected function bankFilter(): void
    {
        $this->crud->filter('bank_id')
            ->label(__('starmoozie::title.bank'))
            ->type('select2_ajax')
            ->values(starmoozie_url('filter/bank'))
            ->minimum_input_length(0)
            ->whenActive(fn ($value) => $this->crud->addClause('where', 'bank_id', $value))
            ->apply();
    }

    /**
     * Fiter by transaction category
     */
    protected function transactionCategoryFilter(array $transaction_types): void
    {
        $this->crud->filter('transactioncategory')
            ->label(__('starmoozie::title.category'))
            ->type('select2_multiple')
            ->values(TransactionCategory::whereIn('type', $transaction_types)->pluck('name', 'id')->toArray())
            ->whenActive(fn ($value) => $this->crud->addClause('selectByTransactionCategory', \json_decode($value)))
            ->apply();
    }
}
