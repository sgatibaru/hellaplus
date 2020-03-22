<?php
namespace App\Filters;

use App\Libraries\IonAuth;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoggedInFilter implements FilterInterface
{
    /**
     * @var IonAuth
     */
    private $ionAuth;
    private $session;

    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request)
    {
        $this->session = \Config\Services::session();
        $this->ionAuth = new IonAuth();
        if(!$this->ionAuth->loggedIn()) {
            $this->session->setFlashdata('message', "Please login to continue");
            return redirect()->to(site_url('auth/login'));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement after() method.
    }
}