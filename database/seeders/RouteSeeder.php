<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Starmoozie\LaravelMenuPermission\app\Models\Route;

class RouteSeeder extends Seeder
{
    protected $data = [
        [
            'route'        => 'filter/role', // Name of route menu
            'method'       => 'get', // crud, get, post, put, patch, delete
            'controller'   => 'Api\RoleApiController@filter', // Name of controller
            'type'         => 'dashboard_api' // dashboard, api, dahsboard_api, web
        ],
        [
            'route'        => 'filter/user', // Name of route menu
            'method'       => 'get', // crud, get, post, put, patch, delete
            'controller'   => 'Api\UserApiController@filter', // Name of controller
            'type'         => 'dashboard_api' // dashboard, api, dahsboard_api, web
        ],
        [
            'route'        => 'filter/transactioncategory', // Name of route menu
            'method'       => 'get', // crud, get, post, put, patch, delete
            'controller'   => 'Api\TransactionCategoryApiController@filter', // Name of controller
            'type'         => 'dashboard_api' // dashboard, api, dahsboard_api, web
        ],
        [
            'route'        => 'filter/bank', // Name of route menu
            'method'       => 'get', // crud, get, post, put, patch, delete
            'controller'   => 'Api\BankApiController@filter', // Name of controller
            'type'         => 'dashboard_api' // dashboard, api, dahsboard_api, web
        ],
        [
            'route'        => 'fetch/dashboard', // Name of route menu
            'method'       => 'post', // crud, get, post, put, patch, delete
            'controller'   => 'Api\DashboardApiController@filter', // Name of controller
            'type'         => 'dashboard_api' // dashboard, api, dahsboard_api, web
        ],
        [
            'route'        => 'fetch/transactioncategory', // Name of route menu
            'method'       => 'post', // crud, get, post, put, patch, delete
            'controller'   => 'Api\TransactionCategoryApiController@filter', // Name of controller
            'type'         => 'dashboard_api' // dashboard, api, dahsboard_api, web
        ],
        [
            'route'        => 'fetch/bank', // Name of route menu
            'method'       => 'post', // crud, get, post, put, patch, delete
            'controller'   => 'Api\BankApiController@filter', // Name of controller
            'type'         => 'dashboard_api' // dashboard, api, dahsboard_api, web
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $route) {
            Route::updateOrCreate($route, $route);
        }
    }
}
