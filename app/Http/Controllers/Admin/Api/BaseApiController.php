<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    protected $selectColumns = [
        "name",
        "id"
    ];

    /**
     * default all model query
     */
    protected function defaultQuery($request)
    {
        return (new $this->model)
            ->where($this->column, "LIKE", "%{$request->q}%")
            ->select($this->selectColumns)
            ->orderBy($this->column);
    }

    /**
     * Filter list operations
     */
    public function filter(Request $request)
    {
        return $this->defaultQuery($request)
            ->pluck($this->selectColumns[0], $this->selectColumns[1]);
    }

    /**
     * Fetch field operations
     */
    public function fetch(Request $request)
    {
        return $this->defaultQuery($request)->paginate(10);
    }
}
