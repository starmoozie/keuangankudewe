<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Bank as Model;
use Illuminate\Http\Request;

class BankApiController extends BaseApiController
{
    protected $model  = Model::class;
    protected $column = "name";

    /**
     * Fetch field operations
     */
    public function fetch(Request $request)
    {
        $form = collect($request->form);

        $referer = $form->where('name', '_http_referrer')->first()['value'];

        // Identity if from mutation page
        if (\str_contains($referer, 'mutation') && $request->dependencies === "from") {
            
            $from = $form->where('name', $request->dependencies)->first()['value'];

            if (!$from) {
                return collect([]);
            } else {
                return $this->defaultQuery($request)->where('id', '!=', $from)->paginate(10);
            }
        }

        return $this->defaultQuery($request)->paginate(10);
    }
}
