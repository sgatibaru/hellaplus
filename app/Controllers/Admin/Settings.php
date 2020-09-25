<?php


namespace App\Controllers\Admin;


class Settings extends DashController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = "Settings";
    }

    public function index()
    {
        if($this->request->getPost()) {
            $msg = "Settings ";
            //Save the settings
            update_option('site_name', $this->request->getPost('site_name'));
            if($logo = $this->request->getFile('logo')) {
                if($logo->isValid() && !$logo->hasMoved()) {
                    $name = $logo->getRandomName();
                    if($logo->move(FCPATH.'uploads/app', $name, true)) {
                        update_option('site_logo', base_url('uploads/app/'.$name));
                        $msg .= "and logo ";
                    }
                }
            }
            $allow_registration = (bool)$this->request->getPost('allow_registration');
            update_option('allow_registration', $allow_registration ? '1' : '0');
            $msg .= "updated successfully";

            $response = [
                'status'    => 'success',
                'title'     => 'Settings',
                'message'   => $msg,
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($response));
        }
        return $this->_render('dashboard/settings/index', $this->data);
    }
}