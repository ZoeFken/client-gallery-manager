<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor contact pagina
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1
 */
class Contact extends MY_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('contact_model');
    }

    public function index()
    {
		$info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_contact');

        switch(TRUE)
        {
            case ($this->checkAcces(KLANT, FALSE)):
            $info['auth'] = 'klant';
            break;
            case ($this->checkAcces(ADMIN, FALSE)):
            $info['auth'] = 'admin';
            break;
            default:
            $info['auth'] = 'visitor';
            break;
        }
        
        $this->loadLang(FORM);

        $this->load->view('templates/header', $info);
        $this->load->view('general/contact');
        $this->load->view('templates/footer');
    }

    /**
     * Controle op data voor email
     */
    public function sendEmail()
    {
        $this->contactValidation();
        if(!$this->reCaptchaValidation())
        {
            $this->session->set_tempdata('error', 'De reCaptcha is foutief ingevuld', 100);
            $this->myRedirect();
        }

        $emailData = [
            'email' => $this->input->post('email'),
            'name' => $this->input->post('name'),
            'message' => nl2br(htmlentities($this->input->post('message'), ENT_QUOTES, 'UTF-8'))
        ];

        if($this->send($emailData))
        {
            $this->session->set_tempdata('msg','De email is goed verstuurd', 100);
        }

        $this->myRedirect();
    }

    /**
     * Validatie van een nieuwe gebruiker
     */
    private function contactValidation()
    {       
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|min_length[3]');

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_tempdata('error', validation_errors(), 100);
            redirect(base_url() . 'contact');
        }
    }

    /**
     * Validatie van de reCaptcha
     * @return TRUE of FALSE
     */
    private function reCaptchaValidation()
    {
        $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = $this->config->item('google_secret');

        $userIp=$this->input->ip_address(); 

        $response = file_get_contents($url . "?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp);

        $data = json_decode($response);
        
        return (isset($data->success) && $data->success=="true") ? TRUE : FALSE;
    }

    /**
     * Stuur een email naar de klant met activatie gegevens
     * @param $emailData de data nodig voor opmaak email
     * @return success of fail
     */
    private function send($emailData)
    {
        $this->config->load('email_config', TRUE); // TRUE zorgt ervoor dat de array op zich staat.
        $this->load->library('email');
        $this->email->clear();

        $subject = 'Contact formulier Fotografie Sandy';
        $message = $this->load->view('emails/contact_email.php', $emailData, TRUE); // TRUE krijg de data terug zonder het naar de browser te sturen
        $config = $this->config->item('email_config'); // Laad de hele config in $config

        $this->email->initialize($config);
        $this->email->from($config['email_address'], $emailData['name']);
        $this->email->to($config['email_owner']);
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }
}
