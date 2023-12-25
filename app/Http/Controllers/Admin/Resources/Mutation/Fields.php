<?php

namespace App\Http\Controllers\Admin\Resources\Mutation;

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
            ->default(date('Y-m-d'))
            ->size(6);

        $this->crud->field('amount')
            ->label(__('starmoozie::title.amount'))
            ->size(6)
            ->masking(['format' => '#.##0']);

        $this->crud->field('from')
            ->label(__('starmoozie::title.from'))
            ->type('relationship')
            ->ajax(true)
            ->data_source(starmoozie_url('fetch/bank'))
            ->minimum_input_length(0)
            ->size(6);

        $this->crud->field('to')
            ->label(__('starmoozie::title.to'))
            ->type('relationship')
            ->ajax(true)
            ->data_source(starmoozie_url('fetch/bank'))
            ->minimum_input_length(0)
            ->size(6)
            ->dependencies(['from']);

        $this->crud->field('notes')
            ->label(__('starmoozie::title.note'));
    }
}
