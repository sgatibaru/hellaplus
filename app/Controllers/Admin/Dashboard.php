<?php


namespace App\Controllers\Admin;


class Dashboard extends \App\Controllers\Admin\DashController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        return $this->_render('dashboard/index', $this->data);
    }
}