<?php

namespace App\Http\Controllers\Admin\Resources\Report;

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
        $this->dateRangeFilter('dates', 'selectByDatesRange');

        $this->bankFilter();

        $this->crud->filter('type')
            ->type('select2')
            ->values(fn () => $this->getTypeOptions())
            ->whenActive(function ($value) {
                $this->crud->addClause('where', 'is_income', $value);
            });
    }

    /**
     * Get transaction type
     */
    private function getTypeOptions(): array
    {
        $options = [];
        foreach (TransactionConstant::ALL as $value) {
            if ($value['value'] !== TransactionConstant::BOTH) {
                $options[$value['value']] = __("starmoozie::title.{$value['label']}");
            }
        }

        return $options;
    }
}
