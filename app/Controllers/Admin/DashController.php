<?php


namespace App\Controllers\Admin;


class DashController extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'Admin Dashboard';
        $this->data['admin_dashboard'] = true;
    }

    public function _render($view, $data = [], $return = false)
    {
        $html = view($view, $data);
        $data['the_content'] = $html;

        $html = view('dashboard/layout', $data);

       // $html = view($view, $data);
        $data['_content'] = $html;

        $content = view('admin_layout', $data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}