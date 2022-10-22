<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
		$this->loadLang(NAVIGATION);
		$this->loadLang(TITLES);
    }

    /**
     * Load het taal bestand
     * 
     * @param $page welke pagina wil je laden
     */
    protected function loadLang($page)
    {
        $lang = (!empty($this->session->userdata('lang'))) ? $this->session->userdata('lang') : $this->config->item('language');
        $this->lang->load($page, $lang);
    }

    /**
     * Controlle toegang aan de hand van een constante
     * Indien de auth level niet ok is wordt de persoon herlijd naar een andere pagina
     * @param $auth de constante auth level
     * @param $redirect moet er geredirect worden
     */
    protected function checkAcces($auth, $redirect = TRUE)
    {
        $this->authorize->setRights($this->session->userdata('auth'));
        if(!$this->authorize->checkAllow($auth))
        {
            if($redirect)
            {
                $this->myRedirect();
            }
            return false;
        }
        return true;
    }

    /**
     * Redirect de ingelogede gebruiker naar de juiste start pagina
     */
    protected function myRedirect()
    {
        $this->authorize->setRights($this->session->userdata('auth'));
        if($this->authorize->checkAllow(KLANT)) 
        {
			redirect(base_url() . 'client');
        }
        
        if($this->authorize->checkAllow(ADMIN))
        {
            redirect(base_url() . 'overview');
		}
		
        redirect(base_url() . 'login');
    }

    /**
     * Kijk of een gebruiker toegang heeft tot de galerij methodes
     * @param $gallery_id
     * @return true of false
     */
    protected function personalGalleryAccess($gallery_id)
    {
        $this->load->model('user_model');
        $user_id = $this->session->userdata('id');

        if (!$this->user_model->isUserOwnerGallery($gallery_id, $user_id))
        {
            $this->logging->Log($this->session->userdata('id'), '10', 'The user ' . $user_id . ' tryed to access data from gallery ' . $gallery_id);
            $this->session->set_flashdata('clienterror', 'U heeft geen toegang tot deze data');
            $this->myRedirect();
        }
    }
}
