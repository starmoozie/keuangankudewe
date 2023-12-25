<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Models\Bank;

trait IncomeExpenseDefaultLogic
{
    /**
     * Default Create/Update logic the specified resource in the database.
     *
     * @return \Illuminate\Http\Response
     */
    private function defaultLogic($action)
    {
        $this->crud->hasAccessOrFail($action);

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        try {
            $item = \DB::transaction(function () use ($request, $action) {

                if ($action === "update") {
                    $entry = $this->crud->getCurrentEntry();
                    // update the row in the db
                    $item = $this->crud->update(
                        $request->get($this->crud->model->getKeyName()),
                        $this->crud->getStrippedSaveRequest($request)
                    );
                    $this->data['entry'] = $this->crud->entry = $item;

                    // New balance
                    $new_balance = $item->amount - $entry->amount;
                } else {
                    // create item in the db
                    $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
                    $this->data['entry'] = $this->crud->entry = $item;

                    // New balance
                    $new_balance = $item->amount;
                }

                // Update bank model
                $this->handleCurrentBalance($request->bank, $new_balance);

                return $item;
            });

            // show a success message
            \Alert::success(trans("starmoozie::crud.{$action}_success"))->flash();

            // save the redirect choice for next time
            $this->crud->setSaveAction();

            return $this->crud->performSaveAction($item->getKey());
        } catch (\Throwable $th) {
            \Alert::error($th->getMessage())->flash();

            return \redirect()->back();
        }
    }

    /**
     * Update balance in bank model.
     *
     * @return void
     */
    private function handleCurrentBalance($bank_id, $new_amount)
    {
        $bank    = Bank::find($bank_id);

        $balance = $this->crud->getRequest()->segment(2) === "expense"
            ? $bank->balance - $new_amount
            : $bank->balance + $new_amount;

        $bank->update(['balance' => $balance]);
    }
}
