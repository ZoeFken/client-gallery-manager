<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor toegang te verlenen aan een privé galerij
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.1
 */

class Visitor extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Geen directe toegang
     */
    public function index()
    {
        show_404();
    }

    /**
     * Laat het toe om mensen toegang te geven tot een privé galerij
     */
    public function link()
    {
        $this->load->model('gallery_model');
        $this->load->model('user_model');
        
        if(!$this->uri->segment(3))
        {
           $this->myRedirect();
        }

        $link_unique = $this->uri->segment(3);
        $gallery_id = $this->user_model->isVisitor($link_unique);

        if($gallery_id)
        {
            $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_visitor');
            $info['auth'] = 'visitor';
            $info['css_gallery'] = TRUE;

            $data['images'] = $this->gallery_model->getGalleryImagesData($gallery_id);
            $data['owner'] = $this->gallery_model->whoOwnsGallery($gallery_id);
            $data['folderName'] = $this->gallery_model->getGalleryFolderName($gallery_id);
            $data['selected_gallery'] = $this->gallery_model->getGalleryName($gallery_id);
            $data['link'] = $link_unique;

            $this->load->view('templates/header', $info);
            $this->load->view('visitor/gallery', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            $this->myRedirect();
        }
	}
}
