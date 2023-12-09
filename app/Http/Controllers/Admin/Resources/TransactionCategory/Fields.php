<?php

namespace App\Http\Controllers\Admin\Resources\TransactionCategory;

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
        $type_options = $this->getTypeOptions();

        $this->crud->field('type')
            ->label(__('starmoozie::title.category'))
            ->type('radio')
            ->inline(true)
            ->options($type_options)
            ->default(TransactionConstant::BOTH)
            ->size(6);

        $this->crud->field('name')
            ->label(__('starmoozie::base.name'))
            ->size(6);
    }

    /**
     * Get transaction type
     */
    private function getTypeOptions(): array
    {
        $options = [];
        foreach (TransactionConstant::ALL as $value) {
            $options[$value['value']] = __("starmoozie::title.{$value['label']}");
        }

        return $options;
    }
}
