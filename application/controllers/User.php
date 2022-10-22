<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor het beheren van een gebruiker
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class User extends MY_Controller
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
    public function edit($user_id = NULL)
    {
        $this->checkAcces(ADMIN);

        if($user_id == NULL)
        {
            $this->loadLang(LOGS);
            $this->logging->Log($this->session->userdata('id'), '840', 'No ID included for editing');
            $this->session->set_tempdata('error', $this->lang->line('log_840'), 100);
            $this->myRedirect();
        }

        $editableUser = $this->user_model->getUserById($user_id);

        $user['user'] = $this->user_model->getSelectedUserRights($editableUser);
        $user['address'] = $this->user_model->getUserAddress($user_id);

        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_edit_user');
        $info['auth']  = 'admin';
        $info['reset'] = $this->user_model->getUserResetLink($editableUser['user_email']);

        $this->loadLang(FORM);

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
        $canLogin = $this->canUserLogin($user_id);
        $this->editValidation($user_id);

        $email = $this->input->post('email');

        // Controle of de email reeds bestaad bij een andere gebruiker
        if($this->user_model->doesEmailExistOtherUser($user_id, $email))
        {
            $userData = $this->createUserData($canLogin);
            $this->editUser($userData);
        }
        else
        {
            $this->loadLang(LOGS);
            $this->logging->Log($this->session->userdata('id'), '810', $email . ' allready exists');
            $this->session->set_tempdata('error', $this->lang->line('log_810'), 100);
            redirect(base_url() . 'edit_user/edit/' . $this->input->post('id'));
        }
    }

    /**
     * Validatie van een nieuwe gebruiker
     */
    private function editValidation()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
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
            $this->session->set_tempdata('msg', validation_errors(), 100);
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
        $language = $this->input->post('language');
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
            'user_auth' => $rights,
            'user_language' => $language
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
     * @param $user_id
     * @return true of false
     */
    private function canUserLogin($user_id)
    {
        $user = $this->user_model->getUserById($user_id);

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

        $this->loadLang(LOGS);
        $addressEditMessage = '';
        if ($userHasAddress && $addressEmpty)
        {
            $this->user_model->deleteAddressByUserId($user['user_id']);
            $addressEditMessage = $this->lang->line('log_821');
            $this->logging->Log($this->session->userdata('id'), '821', 'Address has been deleted for user ' . $user['user_id']);
        }
        elseif (!$userHasAddress && !$addressEmpty)
        {
            $address['user_id'] = $user['user_id'];
            $address['address_created_at'] = date('Y-m-d H:i:s');
            $this->user_model->addAddress($address);
            $addressEditMessage = $this->lang->line('log_822');
            $this->logging->Log($this->session->userdata('id'), '822', 'Address has been added for user ' . $user['user_id']);
        }
        elseif(!$addressEmpty && $userHasAddress)
        {
            $this->user_model->editAddress($address);
            $addressEditMessage = $this->lang->line('log_823');
            $this->logging->Log($this->session->userdata('id'), '823', 'Address has been edited for user ' . $user['user_id']);
        }

        if ($this->user_model->editUser($user)) 
        {
            $this->logging->Log($this->session->userdata('id'), '820', 'User ' . $user['user_id'] . ' has been edited');
            $this->session->set_tempdata('msg', $this->lang->line('log_820') . ' ' . $addressEditMessage, 100);
        }
        else
        {
            $this->logging->Log($this->session->userdata('id'), '830', 'User ' . $user['user_id'] . ' could not be edited');
            $this->session->set_tempdata('error', $this->lang->line('log_830'), 100);
        }

        $this->myRedirect();
    }

    /**
     * Deactiveer een gebruiker
     * @param $user_id
     */
    public function deactivateClient($user_id)
    {
        $this->checkAcces(ADMIN);
        $this->load->library('authorize', '', 'deactivate_user');

        $userAuthorization = $this->user_model->getUserAuth($user_id);
        $this->deactivate_user->setRights($userAuthorization);

        $this->loadLang(LOGS);
        if($this->deactivate_user->checkAllow(LOGIN))
        {
            $this->deactivate_user->revoke(LOGIN);
            $newAuthorization = $this->deactivate_user->getRightsDecimal();

            $databaseData = [
                'user_id' => $user_id,
                'user_auth' => $newAuthorization,
                'user_password' => '',
                'user_updated_at' => date('Y-m-d H:i:s')
            ];

            if($this->user_model->setUserData($databaseData))
            {
                $this->logging->Log($this->session->userdata('id'), '850', 'Revoked user ' . $user_id . ' login rights');
                $this->session->set_tempdata('msg', $this->lang->line('log_850'), 100);
            }
            else
            {
                $this->logging->Log($this->session->userdata('id'), '860', 'Could not revoke user ' . $user_id . ' login rights');
                $this->session->set_tempdata('error', $this->lang->line('log_860'), 100);
            }

            $this->myRedirect();
        }

        $this->session->set_tempdata('error', $this->lang->line('log_deactivated'), 100);
        $this->myRedirect();
    }

    /**
     * Verwijder een gebruiker uit het systeem
     * @param $user_id
     */
    public function deleteUser($user_id)
    {
        $this->loadLang(LOGS);
        $this->load->model('gallery_model');
        if($this->gallery_model->getGallerys($user_id))
        {
            $this->logging->Log($this->session->userdata('id'), '870', 'Could not delete user ' . $user_id . ', the user stil has a gallery');
            $this->session->set_tempdata('error', $this->lang->line('log_870'), 100);
            redirect(base_url() . 'overview/inactive');
        }

        if($this->user_model->removeUser($user_id))
        {
            $this->logging->Log($this->session->userdata('id'), '880', 'The user ' . $user_id . ' has been removed from the database');
            $this->session->set_tempdata('msg', $this->lang->line('log_880'), 100);
        }
        else
        {
            $this->logging->Log($this->session->userdata('id'), '890', 'The user ' . $user_id . ' could not be removed');
            $this->session->set_tempdata('error', $this->lang->line('log_890'), 100);
        }

        $this->myRedirect();
    }
}
