<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor de login van een gebruiker
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Login extends MY_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('login_model');
    }
 
    /**
     * Login pagina
     */
    public function index()
    {
        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_login');
        $info['auth']  = '';

        $this->loadLang(FORM);
        
        $this->load->view('templates/header', $info);
        $this->load->view('general/login_view');
        $this->load->view('templates/footer');
    }
 
    /**
     * Authenticate een gebruiker
     */
    public function authenticate()
    {
        $this->loginFormValidation();
        $user = $this->checkLoginData();
        $this->createSession($user);
        $this->login();
    }

    /**
     * Validate het login formulier
     */
    private function loginFormValidation()
    {        
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_tempdata('error', validation_errors(), 100);
            redirect(base_url() . 'login');
        }
    }

    /**
     * Controle van de data
     * @return $user de geverifieerde gebruiker
     */
    private function checkLoginData()
    {
        $this->loadLang(LOGS);

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user = $this->login_model->checkLoginDetails($email,$password);
        
        if($user == NULL)
        {
            $this->logging->Log(0, '610', 'Unable to login using ' . $email);
            echo $this->session->set_tempdata('error',$this->lang->line('log_610'), 100);
            redirect(base_url() . 'login');
        }

        return $user;
    }

    /**
     * Initiate the session with the importand data
     * @param $user de geverifieerde gebruiker
     */
    private function createSession($user)
    {
        $id = $user['user_id'];
        $name = $user['user_name'];
        $email = $user['user_email'];
        $auth = $user['user_auth'];
        $lang = $user['user_language'];
        $location = 'active';
        $sesdata = array(
            'id' => $id,
            'username' => $name,
            'email' => $email,
            'auth' => (int) $auth,
            'location' => $location,
            'lang' => $lang
        );
        $this->session->set_userdata($sesdata);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    /**
	 * Zet de rechten en log in
	 */
	private function login()
	{
        $this->authorize->setRights($this->session->userdata('auth'));
        $this->logging->Log($this->session->userdata('id'), '620', 'User has logged in.');
		$this->myRedirect();
	}
}
