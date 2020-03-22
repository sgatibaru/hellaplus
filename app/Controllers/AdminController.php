<?php


namespace App\Controllers;
use \App\Libraries\IonAuth;
use \CodeIgniter\Session\Session;
use Config\Services;

class AdminController extends BaseController
{
    /** @var IonAuth */
    private $ionAuth;
    /**
     * @var Session
     */
    private $session;
    public $data;

    public function __construct()
    {
        $this->session = Services::session();
        $this->ionAuth = new \App\Libraries\IonAuth();

        $this->data['user'] = $this->ionAuth->user()->row();
        $this->data['title'] = 'Dashboard';
    }

    public function _renderPage($view, $data = [], $return = false) {
        $html = view($view, $data);
        $data['_content'] = $html;

        $content = view('admin_layout', $data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}