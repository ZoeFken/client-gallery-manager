<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor de activatie van een gebruiker
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */
class Activation extends MY_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->checkAcces(ADMIN);
    }

    /**
     * Update de data in de database - mag inloggen
     * Genereer een private key voor de paswoord generatie
     * Verstuur een activatie email naar een klant
     * @param $user_id
     */
    public function activateUser($user_id)
    {
        $this->load->model('activation_model');
        $userWhoNeedsLogin = $this->activation_model->getUserById($user_id);
        $randomString = $this->activation_model->createRandomString();
        $user = $this->activation_model->grantLogin($userWhoNeedsLogin);
        $this->ifNullRedirect($user, $randomString);

        $queryKeyData = $this->generateQueryKeyData($user, $randomString);

        $inserted = (!$this->activation_model->checkResetEmail($user['user_email'])) ? 
            $this->activation_model->insertResetKey($queryKeyData) :
            $this->activation_model->replaceResetKey($queryKeyData);

        if($userWhoNeedsLogin['user_auth'] != $user['user_auth'])
        {
            $this->activation_model->updateLoginRightDB($user);
        }

        $emailData = $this->emailData($inserted, $user, $randomString);
        $this->succesOrFailEmail($emailData);
    }

    /**
     * Genereer een data array voor activatie email
     * @param $inserted is de data correct in de database opgenomen
     * @param $user de specifieke gebruikers data
     * @param $key de unieke key gegenereerd en gestockeerd in de database
     * @return $data de data voor de email generatie
     */
    private function emailData($inserted, $user, $key)
    {
        if($inserted)
        {
            $data = 
            [
                'user' => $user['user_name'] . ' ' . $user['user_firstname'],
                'email' => $user['user_email'],
                'key' => $key
            ];
            return $data;
        }

        return NULL;
    }

    /**
     * Genereer de query data voor de reset key
     * @param $user de gebruiker
     * @param $randomString de key aangemaakt voor de gebruiker
     * @return $queryData de data nodig voor de database
     */
    private function generateQueryKeyData($user, $randomString)
    {
        $email = $user['user_email'];
        $queryData = [ 
            'user_email' => $email,
            'reset_token' => $randomString, 
            'reset_created_at' => date('Y-m-d H:i:s') 
        ];

        return $queryData;
    }

    /**
     * Als iets leeg is redirect
     * @param $user een gebruiker
     * @param $randomString een random string
     */
    private function ifNullRedirect($user, $randomString)
    {
        if ($user === NULL)
        {
            $this->logging->Log($this->session->userdata('id'), '410', 'No user could be found');
            $this->session->set_tempdata('error',$this->lang->line('log_410'), 100);
            $this->myRedirect();
        }

        if ($randomString === NULL)
        {
            $this->logging->Log($this->session->userdata('id'), '420', 'No unique key could be found');
            $this->session->set_tempdata('error',$this->lang->line('log_420'), 100);
            $this->myRedirect();
        }
    }

    /**
     * Het versturen van een email en nodige logs aanmaken
     * @param $emailData al de data nodig voor het versturen van een mail
     */
    private function succesOrFailEmail($emailData)
    {
        if($emailData === NULL)
        {
            $this->logging->Log($this->session->userdata('id'), '440', 'There is no email data');
            $this->session->set_tempdata('error',$this->lang->line('log_440'), 100);
            $this->myRedirect();
        }

        if($this->sendEmail($emailData))
        {
            $this->logging->Log($this->session->userdata('id'), '430', 'The email has been send to ' . $emailData['email']);
            $this->session->set_tempdata('msg',$this->lang->line('log_430') . ' ' . $emailData['email'], 100);
        }
        else
        {
            $this->logging->Log($this->session->userdata('id'), '450', 'The email could not be send to ' . $emailData['email']);
            $this->session->set_tempdata('error',$this->lang->line('log_450'), 100);
        }

        $this->myRedirect();
    }

    /**
     * Stuur een email naar de klant met activatie gegevens
     * @param $data de data nodig voor opmaak email
     * @return success of fail
     */
    private function sendEmail($data)
    {
        $this->config->load('email_config', TRUE); // TRUE zorgt ervoor dat de array op zich staat.
        $this->load->library('email');
        $this->email->clear();

        $data['link'] = base_url() . 'password/savePassword/' . $data['key'];

        $subject = 'Activatie email Fotografie Sandy';
        $message = $this->load->view('emails/activation_email.php', $data, TRUE); // TRUE krijg de data terug zonder het naar de browser te sturen
        $config = $this->config->item('email_config'); // Laad de hele config in $config

        $this->email->initialize($config);
        $this->email->attach(base_url() . 'assets/docs/AlgemeneVoorwaarden.pdf');
        $this->email->from($config['email_address'], $config['owner'] );
        $this->email->to($data['email']);
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }
}
