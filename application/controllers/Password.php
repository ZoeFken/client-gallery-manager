<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Creatie van een paswoord
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Password extends MY_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('password_model');
    }

    /**
     * Standaard afhandeling
     * @param $key NULL of toegevoegd aan url
     */
    public function savePassword ($key = null)
    {
        $email = $this->password_model->getEmailThroughKey($key);
        if (empty($email))
        {
            redirect(base_url() . 'login');
        }

        $data['key'] = $key;
        $data['email'] = $email;
        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_password_registration');
        $info['auth'] = 'admin';

        $this->loadLang(FORM);
        
        $this->load->view('templates/header', $info);
        $this->load->view('general/create_password', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Het opslagen van een paswoord van een gebruiker
     */
    public function save()
    {
        $this->generatePasswordFormValidation();

        $user_email = $this->input->post('email');
        $passwordData = $this->createPasswordData($user_email);

        $this->passwordUpdate($passwordData);

        $this->loadLang(LOGS);

        $this->logging->Log($this->session->userdata('id'), '730', 'Error with creating the password');
        $this->session->set_tempdata('error', $this->lang->line('log_730'), 100);
        redirect(base_url() . 'login');
    }

    /**
     * Validatie van de input velden voor de paswoord generatie
     */
    private function generatePasswordFormValidation()
    {        
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
        $this->form_validation->set_rules('checkpassword', 'Checkpassword', 'matches[password]');

        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_tempdata('msg', validation_errors(), 100);
            // URL + KEY
            redirect(base_url() . 'password/savePassword/' . $this->input->post('key'));
        }
    }

    /**
     * Creatie database data
     * @param $user_email
     */
    public function createPasswordData($user_email)
    {
        $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

        $data = array(
            'user_email' => $user_email,
            'user_password' => $password
        );

        return $data;
    }

    /**
     * Update het paswoord
     * @param $passwordData
     */
    private function passwordUpdate($passwordData)
    {
        if($this->password_model->updatePassword($passwordData))
        {
            $this->logging->Log('0', '710', 'Password added for ' . $passwordData['user_email']);

            if($this->password_model->deleteResetEntry($passwordData['user_email']))
            {
                $this->logging->Log('0', '720', 'The reset entry for ' . $passwordData['user_email'] . ' was removed.');
            }

            $this->session->sess_destroy();
            redirect(base_url() . 'login');
        }
    }

    /**
     * Editeer je paswoord pagina
     */
    public function editMyPassword()
    {
        $this->checkAcces(LOGIN);
        $email = $this->session->userdata('email');

        if (empty($email))
        {
            $this->loadLang(LOGS);
            $this->logging->Log($this->session->userdata('id'), '740', 'Could not load edit password, no email selected');
            $this->session->set_tempdata('error', $this->lang->line('log_740'), 100);
            $this->myRedirect();
        }

        $data['email'] = $email;

        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_edit_password');
        $info['auth'] = $this->authorize->checkAllow(ADMIN) ? 'admin' : 'klant';

        $this->loadLang(FORM);
        
        $this->load->view('templates/header', $info);
        $this->load->view('general/edit_password', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Editeer je paswoord
     */
    public function editPassword()
    {
        $this->checkAcces(LOGIN);
        $this->editPasswordFormValidation();

        $user_email = $this->input->post('email');
        $oldPassword = $this->input->post('oldpassword');
        $newPassword = $this->input->post('password');

        if($this->password_model->checkPassword($user_email, $oldPassword))
        {
            $databaseData = $this->createPasswordData($user_email);
            $this->passwordUpdate($databaseData);
        }

        $this->loadLang(LOGS);
        $this->logging->Log($this->session->userdata('id'), '730', 'Could not update the password');
        $this->session->set_tempdata('error', $this->lang->line('log_730'), 100);

        redirect(base_url() . 'password/editMyPassword');
    }

    /**
     * Validatie van de input velden voor de paswoord aanpassing
     */
    private function editPasswordFormValidation()
    {        
        $this->form_validation->set_rules('oldpassword', 'Oldpassword', 'required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
        $this->form_validation->set_rules('checkpassword', 'Checkpassword', 'matches[password]');

        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_tempdata('error', validation_errors(), 100);
            redirect(base_url() . 'password/editMyPassword/');
        }
    }
}
