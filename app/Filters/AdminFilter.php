<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements \CodeIgniter\Filters\FilterInterface
{
    /**
     * @var \App\Libraries\IonAuth
     */
    private $ionAuth;
    private $session;

    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request)
    {
        $this->ionAuth = new \App\Libraries\IonAuth();
        $this->session = \Config\Services::session();
        if(!$this->ionAuth->loggedIn() || !$this->ionAuth->isAdmin()) {
            $this->session->setFlashdata('message', "You must be and administrator to access this page");
            return redirect()->back();
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {

    }
}