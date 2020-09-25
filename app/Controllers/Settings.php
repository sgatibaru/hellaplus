<?php


namespace App\Controllers;


use App\Models\BusinessModel;

class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'Shortcode Settings';
    }

    public function sms()
    {
        if ($data = $this->request->getPost()) {
            unset($data['id']);
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
            $id = $data['id'];
            update_option($id.'_sms_template', $this->request->getPost('sms_template'));
            $active = (bool) $this->request->getPost('sms_active') ? '1' : NULL;
            update_option($id.'_sms_active', $active);
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

    public function shortcodes() {
        $bs = new BusinessModel();
        $this->data['shortcodes'] = $bs->findAll();
        return $this->_renderPage('admin/shortcodes', $this->data);
    }
}