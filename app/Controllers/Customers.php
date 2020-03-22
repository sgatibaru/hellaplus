<?php


namespace App\Controllers;


use App\Models\CustomerModel;

class Customers extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'My Customers';
    }

    public function index()
    {
        if (!active_business()) {
            return $this->_renderPage('admin/empty', $this->data);
        } else {
            return $this->_renderPage('admin/customers', $this->data);
        }
    }

    public function activate($id) {
        $data = [
            'id'    => $id,
            'status'    => 1
        ];
        //$users = new CustomerModel();
        //$user = $users->find($id);
        //$user->status = 1;
        if(\Config\Database::connect()->table('customers')->where('id', $id)->update(['status'=>1])){
            $response = [
                'status'    => 'success',
                'title'     => 'Customer activated',
                'message'   => 'Customer will receive SMS from this app',
                'callback'  => 'window.location.reload()'
            ];
        } else {
            $response = [
                'status'    => 'error',
                'title'     => 'Error',
                'message'   => 'An unknown error occured.'
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function deactivate($id) {
        $data = [
            'id'    => $id,
            'status'    => 1
        ];

        if(\Config\Database::connect()->table('customers')->where('id', $id)->update(['status'=>0])){
            $response = [
                'status'    => 'success',
                'title'     => 'Customer deactivated',
                'message'   => 'Customer will not receive any SMS from this app',
                'callback'  => 'window.location.reload()'
            ];
        } else {
            $response = [
                'status'    => 'error',
                'title'     => 'Error',
                'message'   => 'An unknown error occured.'
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function send_single_sms() {
        if($this->request->getPost()) {
            $sms = new \App\Libraries\SMS();
            $resp = $sms->send_sms($this->request->getPost('phone'), $this->request->getPost('message'));
            if($resp){
                $response = [
                    'status'    => 'success',
                    'title'     => 'SMS Sent',
                    'message'   => 'Message Sent',
                    'callback'  => '$(\'.modal\').modal(\'hide\');'
                ];
            } else {
                $response = [
                    'status'    => 'warning',
                    'title'     => 'A problem occured',
                    'message'   => 'Unknown problem occured',
                    'callback'  => '$(\'.modal\').modal(\'hide\');'
                ];
            }
        } else {
            $response = [
                'status'    => 'error',
                'title'     => 'Invalid Request',
                'message'   => 'Invalid request',
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function send_bulk_sms() {
        if($this->request->getPost()) {
            $sms = new \App\Libraries\SMS();
            $phones = (new CustomerModel())->where('status', 1)->findAll();
            $numbers = [];
            if($phones && count($phones) > 0) {
                foreach($phones as $p) {
                    array_push($numbers, '+'.$p->phone);
                }
                $phones = implode(', ', $numbers);
                $resp = $sms->send_sms($phones, $this->request->getPost('message'));
                if($resp){
                    $response = [
                        'status'    => 'success',
                        'title'     => 'SMS Sent',
                        'message'   => 'Message Sent',
                        'callback'  => '$(\'.modal\').modal(\'hide\');'
                    ];
                } else {
                    $response = [
                        'status'    => 'warning',
                        'title'     => 'A problem occured',
                        'message'   => 'Unknown problem occured',
                        'callback'  => '$(\'.modal\').modal(\'hide\');'
                    ];
                }
            }else {
                $response = [
                    'status'    => 'warning',
                    'title'     => 'No Customers',
                    'message'   => 'No customers set up to receive SMS',
                    'callback'  => '$(\'.modal\').modal(\'hide\');'
                ];
            }

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