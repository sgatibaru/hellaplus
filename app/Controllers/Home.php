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
		if($bs = active_business()) {
            if($bs->type == 'C2B') {
                return $this->_renderPage('admin/overview', $this->data);
            } else {
                return $this->_renderPage('admin/b2c/overview', $this->data);
            }
        } else {
            return $this->_renderPage('admin/empty', $this->data);
        }
	}

	//--------------------------------------------------------------------
}
