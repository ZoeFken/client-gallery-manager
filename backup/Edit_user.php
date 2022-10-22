<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor het editeren van een gebruiker
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   0.1
 */

class Edit_user extends MY_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->checkAcces(LOGIN);
    }

    /**
     * Editeer een specifieke gebruiker als admin
     * @param $user_id
     */
    public function edit($user_id)
    {
        $this->checkAcces(ADMIN);
        $editableUser = $this->user_model->getUserById($user_id);
        $userWithSelectableRights = $this->user_model->getSelectedRights($editableUser);
        
        $associatedAddress = $this->user_model->getAddress($user_id);

        $user['user'] = $userWithSelectableRights;
        $user['address'] = $associatedAddress;

        $info['title'] = 'Gebruiker editeren';
        $info['auth']  = 'admin';

        $this->load->view('templates/header', $info);
        $this->load->view('admin/edit_user', $user);
        $this->load->view('templates/footer');
    }
    
    /**
     * Editeer een specifieke gebruik
     */
    public function editSpecificUser()
    {
        $this->checkAcces(ADMIN);
        $user_id = $this->input->post('id');
        $canLogin = $this->userCanLogin($user_id);
        $this->editValidation($user_id);

        $email = $this->input->post('email');

        // Controle of de email reeds bestaad bij een andere gebruiker
        if($this->user_model->isUniqueUser($user_id, $email))
        {
            $userData = $this->createUserData($canLogin);
            $this->editUser($userData);
        }
        else
        {
            $this->session->set_flashdata('error', 'De email bestaad al bij een andere gebruiker!');
            redirect(base_url() . 'edit_user/edit/' . $this->input->post('id'));
        }
    }

    /**
     * Validatie van een nieuwe gebruiker
     */
    private function editValidation()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|alpha_numeric_spaces|min_length[3]');
        $this->form_validation->set_rules('firstname', 'Firstname', 'trim|required|alpha_numeric_spaces|min_length[3]');
        $this->form_validation->set_rules('adres', 'Adres', 'trim|alpha_numeric_spaces|min_length[3]');
        $this->form_validation->set_rules('postalcode', 'Postalcode', 'trim|alpha_numeric|min_length[3]');
        $this->form_validation->set_rules('city', 'City', 'trim|min_length[3]');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim|min_length[9]');

        if ($this->form_validation->run() == FALSE) 
        {
            $this->session->set_flashdata('msg', validation_errors());
            redirect(base_url() . 'edit_user/edit/' . $this->input->post('id'));
        }
    }

    /**
     * Creeër de user data
     * @param $canLogin mag deze gebruiker reeds inloggen
     * @return $data de data voor de database
     */
    private function createUserData($canLogin) 
    {
        // user info
        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $name = $this->input->post('name');
        $firstname = $this->input->post('firstname');
        $telephone = $this->input->post('telephone');
        $rights = $this->makeAuthorizationNumber($canLogin);

        // address info
        $street = $this->input->post('street');
        $number = $this->input->post('number');
        $appartment = $this->input->post('appartment');
        $postalcode = $this->input->post('postalcode');
        $city = $this->input->post('city');
        $country = $this->input->post('country');

        $data['user'] = 
        [ 
            'user_id' => $id,
            'user_email' => $email,
            'user_name' => $name, 
            'user_firstname' => $firstname,
            'user_telephone' => $telephone,
            'user_auth' => $rights
        ];

        $data['address'] =
        [
            'user_id' => $id,
            'address_street' => $street,
            'address_number' => $number,
            'address_appartment' => $appartment,
            'address_postalcode' => $postalcode,
            'address_city' => $city,
            'address_country' => $country
        ];

        return $data;
    }

    /**
     * Controle of user kan inloggen
     * @param $id gebruikers id
     * @return true of false
     */
    private function userCanLogin($id)
    {
        $user = $this->user_model->getUserById($id);

        $this->load->library('authorize', '', 'can_login');
        $this->can_login->setRights((int)$user['user_auth']);

        return $this->can_login->checkAllow(LOGIN);
    }

    /**
     * Een autorisastie nummer generen
     * Controlle om te kijken welke rechten er aan staan bij creatie
     * @return decimale representatie auth level
     */
    private function makeAuthorizationNumber($canLogin)
    {
        $this->load->library('authorize', '', 'new_user');
        $var = constant($this->input->post('clientadmin'));

        if($var === KLANT)
        {
            $this->new_user->grant(KLANT);
        }
        if($var === ADMIN)
        {
            $this->new_user->grant(ADMIN);
            // Kan enkele gegeven worden indien admin
            if($this->input->post('createadmin'))
            {
                $this->new_user->grant(CREATEADMIN);
            }
        }
        if($canLogin)
        {
            $this->new_user->grant(LOGIN);
        }

        return $this->new_user->getRightsDecimal();
    }

    /**
     * Stuur de data door voor toevoeging aan de db
     * @return melding bij succes en falen.
     */
    private function editUser($userData)
    { 
        $user = $userData['user'];
        $address = $userData['address'];
        
        $addressEmpty = TRUE;
        $userHasAddress = $this->user_model->userHasAddress($user['user_id']);

        foreach($address as $key => $value)
        {
            if(!empty($value))
            {
                $addressEmpty = FALSE;
                break;
            }
        }

        $addressEditMessage = '';
        if ($userHasAddress && $addressEmpty)
        {
            $this->user_model->deleteAddressByUserId($user['user_id']);
            $addressEditMessage = 'en het adres is verwijderd';
        }
        elseif (!$userHasAddress && !$addressEmpty)
        {
            $address['user_id'] = $user['user_id'];
            $this->user_model->add_address($address);
            $addressEditMessage = 'en het adres is toegevoegd';
        }
        elseif(!$addressEmpty && $userHasAddress)
        {
            $this->user_model->edit_address($address);
            echo "<pre>";
            var_dump($address);
            echo "<pre>";
            $addressEditMessage = 'en het adres is aangepast';
        }

        if ($this->user_model->edit_user($user)) 
        {
            $this->session->set_flashdata('msg', 'De gebruiker is upgedate' . ' ' . $addressEditMessage);
        }
        else
        {
            $this->session->set_flashdata('msg', 'Er is een fout opgetreden bij het updaten');
        }

        $this->myRedirect();
    }

    /**
     * Editeer je password
     * @param $id een gebruikers id
     */
    public function editMyPassword($id)
    {
        $this->authorize->setRights($this->session->userdata('auth'));
        if(!$this->authorize->checkAllow(KLANT) || !$this->authorize->checkAllow(ADMIN))
        {
            $this->myRedirect();
        }
    }

    /**
     * Zet een gebruiker op inactief
     * @param $user_id
     */
    public function inactiveClient($user_id)
    {
        $this->checkAcces(ADMIN);
        
    }

    /**
     * Verwijder een gebruiker volledig uit het systeem
     * @param $user_id
     */
    public function removeClient($user_id)
    {
        $this->checkAcces(ADMIN);
        
    }
}