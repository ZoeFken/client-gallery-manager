<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor klant pagina's
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.1
 */
class Client extends MY_Controller
{
    public function __construct() 
    {
        parent::__construct();
        if (!($this->checkAcces(KLANT, FALSE) || $this->checkAcces(ADMIN, FALSE)))
        {
            $this->myRedirect();
        }
        $this->load->model('gallery_model');
    }

    /**
     * Standaard redirect van de gebruiker.
     */
    public function index()
    {
        $this->client();
    }
 
    /**
     * Main page van de klant
     * @param $gallery_id
     */
    public function client($gallery_id = NULL)
    {
        $this->checkAcces(KLANT);
		$info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_client');
        $info['auth']  = 'klant';
        $info['css_gallery'] = TRUE;

        $user_id = $this->session->userdata('id');
        $gallerys = $this->gallery_model->getGallerys($user_id);

        if($gallery_id === NULL)
        {
            $gallery_id = $gallerys[0]['gallery_id'];
        }

        $data['gallerys'] = $gallerys;
        $data['images'] = $this->gallery_model->getGalleryImagesData($gallery_id);
        $data['ammount_selected'] = $this->gallery_model->getAmmountImagesSelected($gallery_id);
        $data['ammount_included'] = $this->gallery_model->getAmmountIncluded($gallery_id);
        $data['folderName'] = $this->gallery_model->getGalleryFolderName($gallery_id);
        $data['visitorLink'] = $this->gallery_model->getVisitorLink($gallery_id);
        $data['gallery_id'] = $gallery_id;
        $data['owner'] = $this->gallery_model->whoOwnsGallery($gallery_id);

        foreach($gallerys as $gal)
        {
            if($gal['gallery_id'] == $gallery_id)
            {
                $data['selected_gallery'] = $gal['gallery_name'];
            }
        }

        $this->loadLang(CLIENT_GALLERY);

        $this->load->view('templates/header', $info);
        $this->load->view('client/main', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Het aanmaken van een bezoekers link
     * @param $gallery_id
     */
    public function createVisitorLink($gallery_id = NULL)
    {
        $this->loadLang(LOGS);

        if($gallery_id === NULL)
        {
            $this->logging->Log($this->session->userdata('id'), '510', 'No gallery id found');
            $this->session->set_tempdata('error',$this->lang->line('log_510'), 100);
            $this->myRedirect();
        }

        if($this->gallery_model->getLinkUnique($gallery_id))
        {
            $this->logging->Log($this->session->userdata('id'), '520', 'Visitor link allready exists for gallery ' . $gallery_id);
            $this->session->set_tempdata('error',$this->lang->line('log_520'), 100);
        }

        $this->load->helper('string');

        $linkData = [
            'gallery_id' => $gallery_id,
            'link_unique' => random_string('alnum', 24),
            'link_created_at' => date('Y-m-d H:i:s') 
        ];

        if($this->gallery_model->createVisitorLink($linkData))
        {
            $this->lang->line('message_key');
            $this->logging->Log($this->session->userdata('id'), '530', 'Visitor link created for gallery ' . $gallery_id);
            // $this->session->set_tempdata('msg','Link aangemaakt', 100);
            $this->session->set_tempdata('msg',$this->lang->line('log_530'), 100);
        }
        else
        {
            $this->logging->Log($this->session->userdata('id'), '540', 'Error with creating the visitor link for gallery ' . $gallery_id);
            $this->session->set_tempdata('error',$this->lang->line('log_540'), 100);
        }

        $link = ($this->checkAcces(ADMIN, FALSE)) ? (base_url() . 'gallery/visitGallery/' . $gallery_id) : (base_url() . '/client/' . $gallery_id);
        redirect($link);
    }
    
    /**
     * Verwijder een bezoekers link
     * @param $gallery_id
     */
    public function deleteVisitorLink($gallery_id)
    {
        $this->loadLang(LOGS);
        
        if(!$this->gallery_model->removeVisitorLink($gallery_id))
        {
            $this->logging->Log($this->session->userdata('id'), '550', 'Could not delete the visitor link for gallery ' . $gallery_id);
            $this->session->set_tempdata('error',$this->lang->line('log_550'), 100);
            $this->myRedirect();
        }

        $this->logging->Log($this->session->userdata('id'), '560', 'Visitor link deleted for gallery ' . $gallery_id);
        $this->session->set_tempdata('msg',$this->lang->line('log_560'), 100);

        $link = ($this->checkAcces(ADMIN, FALSE)) ? (base_url() . 'gallery/visitGallery/' . $gallery_id) : (base_url() . '/client/' . $gallery_id);
        redirect($link);
    }
}
