<?php

namespace App\Http\Controllers\Admin\Resources\Expense;

use App\Constants\TransactionConstant;

trait Fields
{
    /**
     * Define create / update form fields.
     * 
     * @return void
     */
    protected function setFields()
    {
        $this->crud->field('dates')
            ->label(__('starmoozie::title.date'))
            ->type('date')
            ->default(date('Y-m-d'))
            ->size(6);

        $this->crud->field('transactionCategory')
            ->label(__('starmoozie::title.category'))
            ->type('relationship')
            ->ajax(true)
            ->data_source(starmoozie_url('fetch/transactioncategory'))
            ->minimum_input_length(0)
            ->size(6)
            ->options(fn ($query) => $query->whereIn('type', Self::TRANSACTION_CATEGORY)->orderBy('name'));

        $this->crud->field('amount')
            ->label(__('starmoozie::title.amount'))
            ->size(4)
            ->masking(['format' => '#.##0']);

        $this->crud->field('bank')
            ->label(__('starmoozie::title.bank'))
            ->type('relationship')
            ->ajax(true)
            ->data_source(starmoozie_url('fetch/bank'))
            ->minimum_input_length(0)
            ->size(4)
            ->options(fn ($query) => $query->orderBy('name'));

        $this->crud->field('details')
            ->label(__('starmoozie::title.used_for'))
            ->type('select2_from_array')
            ->options($this->usedFor())
            ->allows_multiple(true)
            ->size(4);

        $this->crud->field('notes')
            ->label(__('starmoozie::title.note'))
            ->type('textarea');
    }

    private function usedFor()
    {
        $items = [];
        foreach (TransactionConstant::USED_FOR as $item) {
            $items[$item['value']] = __("starmoozie::title.{$item['label']}");
        }

        return $items;
    }
}
