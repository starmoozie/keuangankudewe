<?php

namespace App\Http\Controllers\Admin\Resources\Expense;

use App\Constants\Type;

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
            ->size(6);

        $this->crud->field('amount')
            ->label(__('starmoozie::title.amount'))
            ->size(6)
            ->masking(['format' => '#.##0']);

        $this->crud->field('bank')
            ->label(__('starmoozie::title.bank'))
            ->size(6);

        $this->crud->field('notes')
            ->label(__('starmoozie::title.note'))
            ->type('textarea');
    }

    protected function detailSubfields()
    {
        return [
            [
                'name'       => 'nominal',
                'label'      => __('starmoozie::title.nominal'),
                'prefix'     => 'Rp',
                'wrapper'    => ['class' => 'form-group col-md-4'],
                'attributes' => ['required' => 'required']
            ],
            [
                'name'       => 'banks',
                'type'  => 'relationship',
                'attribute' => 'name',
                'label'      => __('starmoozie::title.nominal'),
                'prefix'     => 'Rp',
                'wrapper'    => ['class' => 'form-group col-md-4'],
                'attributes' => ['required' => 'required']
            ],
            [
                'name'    => 'note',
                'label'   => __('starmoozie::title.note'),
                'type'    => 'textarea',
                'wrapper' => ['class' => 'form-group col-md-8']
            ],
        ];
    }
}
