<?php


namespace App\Controllers\Admin;


use App\Models\UsersModel;

class Clients extends DashController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = "Registered Clients";
    }

    public function index()
    {
        $this->data['title'] = "Registered Clients";

        return $this->_render('dashboard/clients/index', $this->data);
    }

    public function view($id)
    {
        $client = (new UsersModel())->find($id);
        if(!$client) {
            return redirect()->back()->with('error', "Client not found");
        }

        $this->data['title'] = $client->name;
        $this->data['client'] = $client;

        return $this->_render('dashboard/clients/view', $this->data);
    }
}