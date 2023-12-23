<?php

namespace App\Http\Controllers\Admin;

use Starmoozie\CRUD\app\Library\Widget;
use App\Constants\TransactionConstant;
use Illuminate\Routing\Controller;
use App\Models\Transaction;

class DashboardController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(starmoozie_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $this->data['title'] = trans('starmoozie::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('starmoozie::crud.admin')     => starmoozie_url('dashboard'),
            trans('starmoozie::base.dashboard') => false,
        ];

        // Widgets
        Widget::add()
            ->to('before_content')
            ->class('row')
            ->type('div')
            ->content($this->widgets());

        Widget::add()->type('progress_loader');

        return view(starmoozie_view('dashboard'), $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(starmoozie_url('dashboard'));
    }

    /**
     * setup widgets
     */
    private function widgets(): array
    {
        return array_map(function($item) {
            $size  = 12 / count(TransactionConstant::DASHBOARD);
            $label = $item['label'];
            $color = $item['color'];
            $href  = starmoozie_url($item['endpoint']);
            $desc = __("starmoozie::title.{$label}");

            return [
                'wrapper'       => ['class' => "col-md-{$size}"],
                'class'         => "card shadow mb-2",
                'type'          => 'progress_custom',
                'progress'      => 100,
                'progressClass' => "progress-bar bg-{$item['color']}",
                'id'            => $label,
                'description'   => "<a href='{$href}'>{$desc}</a>",
                'hint'          => __("starmoozie::title.hint_{$label}_dashboard")
            ];
        }, TransactionConstant::DASHBOARD);
    }
}
