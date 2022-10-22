<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor de registratie van een nieuwe gebruiker
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Register extends MY_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->checkAcces(ADMIN);
    }

    /**
     * Weergave van de registratie pagina
     */
    public function index()
    {
        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_register');
        $info['auth']  = 'admin';

        $this->loadLang(FORM);

        $this->load->view('templates/header', $info);
        $this->load->view('admin/register_user');
        $this->load->view('templates/footer');
    }

    /**
     * Registreer een gebruiker binnen het systeem.
     * validatie - gebruikers data - toevoeging aan db
     */
    public function register()
    {
        $this->registerValidation();
        $userData = $this->createUserData();
        $this->addUserToDB($userData);
        $this->myRedirect();
    }
 
    /**
     * Validatie van een nieuwe gebruiker
     */
    private function registerValidation()
    {       
        $this->form_validation->set_message('is_unique', 'Email already exists.');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('firstname', 'Firstname', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim|min_length[9]');

        $this->form_validation->set_rules('street', 'Street', 'trim|min_length[3]');
        $this->form_validation->set_rules('number', 'Number', 'trim');
        $this->form_validation->set_rules('appartment', 'Appartment', 'trim');
        $this->form_validation->set_rules('postalcode', 'Postalcode', 'trim|min_length[3]');
        $this->form_validation->set_rules('city', 'City', 'trim|min_length[3]');
        $this->form_validation->set_rules('country', 'Country', 'trim|min_length[3]');


        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_tempdata('error', validation_errors(), 100);
            redirect(base_url() . 'register');
        }
    }

    /**
     * Creeër de user data
     */
    private function createUserData() 
    {
        // user info
        $email = $this->input->post('email');
        $name = $this->input->post('name');
        $firstname = $this->input->post('firstname');
        $telephone = $this->input->post('telephone');
        $rights = $this->makeAuthorizationNumber();

        // address info
        $street = $this->input->post('street');
        $number = $this->input->post('number');
        $appartment = $this->input->post('appartment');
        $postalcode = $this->input->post('postalcode');
        $city = $this->input->post('city');
        $country = $this->input->post('country');

        $creationDate = date('Y-m-d H:i:s');

        $data['user'] = 
        [ 
            'user_email' => $email,
            'user_name' => $name, 
            'user_firstname' => $firstname,
            'user_telephone' => $telephone,
            'user_auth' => $rights,
            'user_created_at' => $creationDate
        ];

        $data['address'] =
        [
            'address_street' => $street,
            'address_number' => $number,
            'address_appartment' => $appartment,
            'address_postalcode' => $postalcode,
            'address_city' => $city,
            'address_country' => $country,
            'address_created_at' => $creationDate
        ];

        return $data;
    }

    /**
     * Een autorisastie nummer generen
     * Controle om te kijken welke rechten er aan staan bij creatie
     * @return decimale representatie level
     */
    private function makeAuthorizationNumber()
    {
        // instancieer een nieuwe authorize library
        $this->load->library('authorize', '', 'new_user');

        $var = constant($this->input->post('clientadmin'));

        if($var === ADMIN)
        {
            $this->new_user->grant(ADMIN);
            // Kan enkele gegeven worden indien admin
            if($this->input->post('createadmin'))
            {
                $this->new_user->grant(CREATEADMIN);
            }
        }
        
        if($var != ADMIN)
        {
            $this->new_user->grant(KLANT);
        }

        return $this->new_user->getRightsDecimal();
    }

    /**
     * Stuur de data door voor toevoeging aan de db
     * @return melding bij succes en falen.
     */
    private function addUserToDB($userData)
    {
        $user = $userData['user'];
        $address = $userData['address'];
        $addressEmpty = TRUE;

        foreach($address as $key => $value)
        {
            if($key != 'address_created_at' && !empty($value))
            {
                $addressEmpty = FALSE;
                break;
            }
        }

        $this->load->model('user_model');
        $user_id = $this->user_model->addUser($user); // id of false
        
        $this->loadLang(LOGS);

        if ($user_id != FALSE)
        {
            $this->logging->Log($this->session->userdata('id'), '310', 'User ' . $user_id . ' has been added to the db.');
            $this->session->set_tempdata('msg', $this->lang->line('log_310'), 100);

            if(!$addressEmpty)
            {
                $address['user_id'] = $user_id;
                $this->user_model->addAddress($address);
                $this->logging->Log($this->session->userdata('id'), '320', 'The address for user ' . $user_id . ' has been added to the db.');
			}
		}
		else
		{
			$this->logging->Log($this->session->userdata('id'), '330', 'The user could not be added to the db');
			$this->session->set_tempdata('error', $this->lang->line('log_330'), 100);
		}
    }
}
