<?php


namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class InstallerFilter implements FilterInterface
{

    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request)
    {
        // TODO: Implement before() method.
        if(file_exists(FCPATH.'env.php')){
            return redirect()->to(site_url());
        }
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement after() method.
    }
}