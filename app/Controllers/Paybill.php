<?php


namespace App\Controllers;


class Paybill extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'Shortcode Settings';
    }

    public function index() {
        if (!active_business()) {
            return $this->_renderPage('admin/empty', $this->data);
        } else {
            return $this->_renderPage('admin/paybill', $this->data);
        }
    }

    public function create() {
        if($this->request->getPost()) {
            $model = new \App\Models\BusinessModel();
            if($model->save($this->request->getPost())) {
                $response = [
                    'status'    => 'success',
                    'title'     => 'Shortcodes Updated',
                    'message'   => 'Shortcodes Updated Successfully',
                    'callback'  => 'window.location.reload()'
                ];
            } else {
                $response = [
                    'status'    => 'error',
                    'title'     => 'Failed to Create Shortcode',
                    'message'   => implode('\n', $model->errors())
                ];
            }
        } else {
            $response = [
                'status'    => 'warning',
                'title'     => 'Invalid Request',
                'message'   => 'Request made is invalid'
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function delete($id = FALSE) {
        $bs = (new \App\Models\BusinessModel())->find($id);
        $response = [
            'status'    => 'error',
            'title'     => 'Failed',
            'message'   => 'Shortcode does not exist'
        ];
        if($bs) {
            if(\Config\Database::connect()->table('businesses')->where('id', $bs->id)->delete()){
                $response = [
                    'status'    => 'success',
                    'title'     => 'Shortcodes Deleted',
                    'message'   => 'Shortcodes Deleted Successfully',
                    'callback'  => 'window.location.reload()'
                ];
            }
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function switch($id) {
        $bs = (new \App\Models\BusinessModel())->find($id);
        $response = [
            'status'    => 'error',
            'title'     => 'Failed',
            'message'   => 'Shortcode does not exist'
        ];
        if($bs) {
            set_option('active_business', $bs->id);
            set_option('active_shortcode', $bs->id);
            $response = [
                'status'    => 'success',
                'title'     => 'Switch Successful',
                'message'   => 'You have successfully switched to '.$bs->name,
                'callback'  => 'window.location.reload(true)'
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }
}