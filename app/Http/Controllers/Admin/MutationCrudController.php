<?php

namespace App\Http\Controllers\Admin;

use App\Models\{
    Bank,
    Mutation as Model
};
use App\Http\Requests\MutationRequest as Request;

class MutationCrudController extends BaseCrudController
{
    use Resources\Mutation\Main;

    protected $model   = Model::class;
    protected $request = Request::class;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        parent::setup();

        $this->crud->addClause('with', 'from');
        $this->crud->addClause('with', 'to');
    }

    public function store()
    {
        $request = $this->crud->getRequest();
        $this->addRequest($request, ['amount' => rupiahToNumber($request->amount), 'created_by' => starmoozie_user()->id]);

        return $this->defaultLogic('create');
    }

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
                $this->handleCurrentBalance($request, $new_balance);

                return $item;
            });

            // show a success message
            \Alert::success(trans("starmoozie::crud.{$action}_success"))->flash();

            // save the redirect choice for next time
            $this->crud->setSaveAction();

            return $this->crud->performSaveAction($item->getKey());
        } catch (\Throwable $th) {
            dd($th->getMessage());
            \Alert::error($th->getMessage())->flash();

            return \redirect()->back();
        }
    }

    public function update()
    {
        $request = $this->crud->getRequest();
        $this->addRequest($request, ['amount' => rupiahToNumber($request->amount)]);

        return $this->defaultLogic("update");
    }

    /**
     * Update balance in bank model.
     *
     * @return void
     */
    private function handleCurrentBalance($request, $new_amount)
    {
        $bank = Bank::whereIn('id', [$request->from, $request->to])->get();

        foreach ($bank as $key => $value) {
            if ($value->id === $request->from) {
                $value->update(['balance' => $value->balance - $new_amount]);
            } else {
                $value->update(['balance' => $value->balance + $new_amount]);
            }
        }
    }
}
