<?php namespace App\Controllers;

class Home extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        //$this->data['title'] = 'HomePage';
    }

    public function index()
	{
		if(!active_business()) {
            return $this->_renderPage('admin/empty', $this->data);
        } else {
            return $this->_renderPage('admin/overview', $this->data);
        }
	}

	//--------------------------------------------------------------------
}
