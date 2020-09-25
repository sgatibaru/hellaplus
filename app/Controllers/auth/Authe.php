<?php

namespace App\Controllers\auth;

use App\Controllers\BaseController;
use App\Libraries\IonAuth;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Session\Session;
use CodeIgniter\Validation\Validation;
use Config\Services;

class Authe extends BaseController
{
    /** @var Validation */
    public $validation;
    /** @var Session */
    public $session;
    /**
     * @var IonAuth
     */
    private $ionAuth;
    private $configIonAuth;
    private $validationListTemplate;

    public function __construct()
    {

        $this->ionAuth = new IonAuth();
        $this->validation = Services::validation();
        helper(['form', 'url']);
        $this->configIonAuth = config('IonAuth');
        $this->session = Services::session();

        if (!empty($this->configIonAuth->templates['errors']['list'])) {
            $this->validationListTemplate = $this->configIonAuth->templates['errors']['list'];
        }
    }

    /**
     * Homepage
     */
    public function index()
    {
        return $this->login();
    }

    /**
     * Login
     */
    public function login()
    {
        if($this->ionAuth->loggedIn()) {
            return redirect()->to(site_url('admin'));
        }
        if ($this->request->getPost()) {
            $this->validation->setRule('identity', 'Email Address', 'required|valid_email');
            $this->validation->setRule('password', 'Password', 'required');
            if ($this->validation->withRequest($this->request)->run()) {
                $remember = (bool)$this->request->getPost('remember');
                if ($this->ionAuth->login($this->request->getPost('identity'), $this->request->getPost('password'), $remember)) {
                    //return redirect()->to(site_url('admin'));
                    $response = [
                        'status'    => 'success',
                        'notify'     => false,
                        'callback'   => 'redirect("'.site_url('admin').'")'
                    ];
                } else {
                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                    //return redirect()->back()->withInput();
                    $response = [
                        'status'    => 'error',
                        'title'     => 'Invalid Credentials',
                        'message'   => implode('. ', $this->ionAuth->errorsArray())
                    ];
                }
            } else {
                $message = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->session->setFlashdata('message', $message);

                //return redirect()->back()->withInput();
                $response = [
                    'status'    => 'error',
                    'title'     => 'Validation Errors',
                    'message'   => $message
                ];
            }

            return $this->response->setContentType('application/json')->setBody(json_encode($response));
        } else {
            //Show the page
            $data['message'] = $this->session->getFlashdata('message');
            $data['title'] = 'Login';

            return $this->_renderPage('auth/login', $data);
        }
    }

    public function register()
    {
        if($this->ionAuth->loggedIn()) {
            return redirect()->to(site_url('admin'));
        }

        if($this->request->getPost()) {
            //Register
            $validation = \Config\Services::validation();
            $validation->setRule('first_name', 'First Name', 'trim|required');
            $validation->setRule('last_name', 'Last Name', 'trim|required');
            $validation->setRule('phone_number', 'Phone Number', 'trim|required|min_length[10]|max_length[13]');
            $validation->setRule('email', 'Email Address', 'trim|required|valid_email');
            $validation->setRule('password', 'Password', 'trim|required|min_length[8]');
            $validation->setRule('password', 'Password', 'trim|required|matches[password]', ['matches' => "Passwords do not match"]);
            if($validation->withRequest($this->request)->run()) {
                $email = $this->request->getPost('email');
                $phone = $this->request->getPost('phone');
                $first_name = $this->request->getPost('first_name');
                $last_name = $this->request->getPost('last_name');
                $password = $this->request->getPost('password');
                $additional = [
                    'first_name' => $first_name,
                    'last_name'     => $last_name,
                    'phone'     => $phone
                ];
                if($this->ionAuth->register($email, $password, $email, $additional)) {
                    $this->session->setFlashdata('message', "Registration successful");
                    $response = [
                        'status'    => 'success',
                        'notify'     => false,
                        'callback'   => 'redirect("'.site_url('auth').'")'
                    ];
                } else {
                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                    //return redirect()->back()->withInput();
                    $response = [
                        'status'    => 'error',
                        'title'     => 'Invalid Credentials',
                        'message'   => implode('. ', $this->ionAuth->errorsArray())
                    ];
                }

            } else {
                $message = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->session->setFlashdata('message', $message);

                //return redirect()->back()->withInput();
                $response = [
                    'status'    => 'error',
                    'title'     => 'Validation Errors',
                    'message'   => $message
                ];
            }

            return $this->response->setContentType('application/json')->setBody(json_encode($response));
        } else {
            $data['message'] = $this->session->getFlashdata('message');
            $data['title'] = 'Login';

            return $this->_renderPage('auth/register', $data);
        }
    }

    public function forgot_password() {
        if($this->request->getPost()) {
            $this->validation->setRule('identity', 'Email Address', 'required|valid_email');
            if($this->validation->withRequest($this->request)->run()) {
                $identityColumn = $this->configIonAuth->identity;
                $identity = $this->ionAuth->where($identityColumn, $this->request->getPost('identity'))->users()->row();
                if (empty($identity))
                {
                    if ($this->configIonAuth->identity !== 'email')
                    {
                        $this->ionAuth->setError('Auth.forgot_password_identity_not_found');
                    }
                    else
                    {
                        $this->ionAuth->setError('Auth.forgot_password_email_not_found');
                    }

                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                    //return redirect()->back()->withInput();
                    $response = [
                        'status'    => 'error',
                        'title'     => 'Validation Errors',
                        'message'   => $this->ionAuth->errors($this->validationListTemplate)
                    ];
                    return $this->response->setContentType('application/json')->setBody(json_encode($response));
                }

                // run the forgotten password method to email an activation code to the user
                $forgotten = $this->ionAuth->forgottenPassword($identity->{$this->configIonAuth->identity});

                if ($forgotten)
                {
                    // if there were no errors
                    $this->session->setFlashdata('message', $this->ionAuth->messages());
                    //return redirect()->to(site_url('auth/login')); //we should display a confirmation page here instead of the login page
                    $response = [
                        'status'    => 'success',
                        'notify'     => false,
                        'callback'   => 'redirect("'.site_url('auth/login').'")'
                    ];
                }
                else
                {
                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                    //return redirect()->back()->withInput();
                    $response = [
                        'status'    => 'success',
                        'notify'     => false,
                        'callback'   => 'redirect("'.site_url('auth/login').'")'
                    ];
                }
                return $this->response->setContentType('application/json')->setBody(json_encode($response));
            } else {
                $message = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->session->setFlashdata('message', $message);
            }
            //return redirect()->back()->withInput();
            $response = [
                'status'    => 'success',
                'notify'     => false,
                'callback'   => 'redirect("'.site_url('auth/login').'")'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($response));
        } else {
            $data['message'] = $this->session->getFlashdata('message');
            $data['title'] = 'Forgot Password';

            return $this->_renderPage('auth/forgot_password', $data);
        }
    }

    public function reset_password($code) {
        if (! $code)
        {
            throw PageNotFoundException::forPageNotFound();
        }
        $user = $this->ionAuth->forgottenPasswordCheck($code);
        if($user) {
            if($this->request->getPost()) {
                $this->validation->setRule('new', 'New Password', 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
                $this->validation->setRule('new_confirm', 'Confirm Password', 'required');
                if($this->validation->withRequest($this->request)->run()) {
                    $identity = $user->{$this->configIonAuth->identity};
                    if($user->id == $this->request->getPost('user_id')) {

                        if($this->ionAuth->resetPassword($identity, $this->request->getPost('new'))) {
                            //$this->session->setFlashdata('message', $this->ionAuth->messages());
                            $response = [
                                'status'    => 'error',
                                'title'     => 'Invalid Credentials',
                                'message'   => $this->ionAuth->messages()
                            ];
                        } else {

                        }
                    } else {
                        $this->ionAuth->clearForgottenPasswordCode($identity);
                        $this->session->setFlashdata('message', 'SECURITY ERROR! Reset code does not match your profile');
                        //return redirect()->back()->withInput();
                        $response = [
                            'status'    => 'error',
                            'title'     => 'Invalid Credentials',
                            'message'   => 'SECURITY ERROR! Reset code does not match your profile'
                        ];
                    }
                } else {
                    $this->session->setFlashdata('message', 'Passwords do not match');
                    //return redirect()->back()->withInput();
                    $response = [
                        'status'    => 'error',
                        'title'     => 'Invalid Credentials',
                        'message'   => 'Passwords do not match'
                    ];
                }
            } else {
                //Show the password reset form
                $data['message'] = $this->session->getFlashdata('message');
                $data['title'] = 'Reset Password';
                $data['user'] = $user;
                return view('auth/reset_password', $data);
            }
        } else {
            $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
            //return redirect()->to(site_url('auth/forgot-password'));
            $response = [
                'status'    => 'success',
                'notify'     => false,
                'callback'   => 'redirect("'.site_url('auth/forgot-password').'")'
            ];
        }

        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function activate($id, $code = '') {
        $admin = false;
        if($this->ionAuth->isAdmin()) {
            $activation = $this->ionAuth->activate($id);
            $admin = true;
        } else {
            $activation = $this->ionAuth->activate($id, $code);
        }
        if($activation) {
            $this->session->setFlashdata('message', $this->ionAuth->messages());
        } else {
            $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
        }

        $admin ? redirect()->back() : redirect()->to(site_url('auth/login'));
    }

    public function logout() {
        $this->data['title'] = 'Logout';

        // log the user out
        $this->ionAuth->logout();

        // redirect them to the login page
        // $this->session->setFlashdata('message', $this->ionAuth->messages());
        return redirect()->to(site_url('auth/login'));
//        $response = [
//            'status'    => 'success',
//            'notify'     => false,
//            'callback'   => 'redirect("'.site_url('auth/login').'")'
//        ];
//        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    private function _renderPage($view, $data = [], $return = false) {
        $html = view($view, $data);
        $data['_content'] = $html;

        $content = view('auth/layout', $data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}