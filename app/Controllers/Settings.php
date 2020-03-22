<?php


namespace App\Controllers;


class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sms()
    {
        if ($data = $this->request->getPost()) {
            foreach($data as $key=>$value){
                update_option($key, $value);
            }
            $response = [
                'status'    => 'success',
                'title'     => 'Settings Updated',
                'message'   => 'SMS Settings Updated Successfully',
            ];
        } else {
            $response = [
                'status'    => 'error',
                'title'     => 'Invalid Request',
                'message'   => 'Invalid request',
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }
    public function sms_templates()
    {
        if ($data = $this->request->getPost()) {
            update_option('sms_template', $this->request->getPost('sms_template'));
            $active = (bool) $this->request->getPost('sms_active') ? '1' : NULL;
            update_option('sms_active', $active);
            $response = [
                'status'    => 'success',
                'title'     => 'Settings Updated',
                'message'   => 'SMS Template Updated Successfully',
            ];
        } else {
            $response = [
                'status'    => 'error',
                'title'     => 'Invalid Request',
                'message'   => 'Invalid request',
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }
}