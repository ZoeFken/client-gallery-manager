<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor de galerijen
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */
class Gallery extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gallery_model');
        $this->checkAcces(ADMIN);
    }

    /**
     * Bezichtig een specifieke klanten galerij
     * @param $gallery_id
     */
    public function visitGallery($gallery_id = NULL)
    {
		$info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_gallery');
        $info['auth']  = 'admin';
        $info['css_gallery'] = TRUE;

        if($gallery_id === NULL)
        {
            $this->loadLang(LOGS);
            
            $this->logging->Log($this->session->userdata('id'), '180', 'No gallery id defined');
            $this->session->set_tempdata('error', $this->lang->line('log_180'), 100);
            $this->myRedirect();
        }
        
        $this->loadLang(ADMIN_GALLERY);
        $this->loadLang(CLIENT_GALLERY);

        $data['selected_gallery'] = $this->gallery_model->getGalleryName($gallery_id);
        $data['owner'] = $this->gallery_model->whoOwnsGallery($gallery_id);
        $data['images'] = $this->gallery_model->getGalleryImagesData($gallery_id);
        $data['ammount_selected'] = $this->gallery_model->getAmmountImagesSelected($gallery_id);
        $data['ammount_included'] = $this->gallery_model->getAmmountIncluded($gallery_id);
        $data['folderName'] = $this->gallery_model->getGalleryFolderName($gallery_id);
        $data['visitorLink'] = $this->gallery_model->getVisitorLink($gallery_id);
        $data['gallery_id'] = $gallery_id;
        $data['gallery_download'] = $this->gallery_model->isDownloaded($gallery_id);

        // echo '<pre>';
        // var_dump($data['gallery_download']);
        // echo '</pre>';
        $this->load->view('templates/header', $info);
        $this->load->view('admin/admin_gallery', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Het aanmaken van een gallerij
     * @param $user_id
     */
    public function createGallery($user_id)
    {
        $this->loadLang(LOGS);

        $this->load->model('user_model');
        if(!$this->user_model->isClient($user_id))
        {
            $this->logging->Log($this->session->userdata('id'), '170', 'The user ' . $user_id . ' is not a client');
            $this->session->set_tempdata('error', $this->lang->line('log_170'), 100);
            $this->myRedirect();
        }

        $this->galleryValidation();

        $galleryData = $this->createGalleryData($user_id);
        $gallery_id = $this->gallery_model->addGalleryToDB($galleryData);

        if($this->gallery_model->createGalleryFolder($galleryData, $gallery_id))
        {
            $this->logging->Log($this->session->userdata('id'), '150', 'Gallery folder created for user ' . $user_id);
            $this->session->set_tempdata('msg', $this->lang->line('log_150'), 100);
        }
        else
        {
            $this->logging->Log($this->session->userdata('id'), '160', 'Gallery folder not created for user ' . $user_id);
            $this->session->set_tempdata('error', $this->lang->line('log_160'), 100);
        }

        $this->myRedirect();
    }

    /**
     * Editeer de galerij
     * @param $gallery_id
     */
    public function editGallery($gallery_id)
    {
        $this->galleryValidation();
        $this->loadLang(LOGS);

        $galleryData = 
        [
            'gallery_id' => $this->input->post('gallery_id'),
            'gallery_included' => $this->input->post('includedImages')
        ];

        if($this->gallery_model->updateGallery($galleryData))
        {
            $this->logging->Log($this->session->userdata('id'), '190', 'Gallery ' . $gallery_id . ' info updated');
            $this->session->set_tempdata('msg', $this->lang->line('log_190'), 100);
        }
        else
        {
            $this->logging->Log($this->session->userdata('id'), '191', 'Could not update gallery ' . $gallery_id . ' info');
            $this->session->set_tempdata('msg', $this->lang->line('log_191'), 100);
        }

        $this->myRedirect();
    }

    /**
     * Validatie van de gegevens van een galerij
     */
    private function galleryValidation()
    {
        /**
         * regex /^[-\w\s]+$/ 
         * \w = letters - getallen - underscores
         * \s = spaties - tabs - line brakes
         * - = koppelteken
         * $ = er moet iets instaan
         */
        $this->form_validation->set_rules('name', 'Name', 'trim|required|regex_match[/^[-\w\s]+$/]');
        $this->form_validation->set_rules('includedImages', 'Included Images', 'trim|required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() == FALSE) 
        {
            $this->session->set_tempdata('error', validation_errors(), 100);
            $this->myRedirect();
        }
    }

    /**
     * Verzamelen en aanmaken gallerij data
     * @param $user_id
     * @return $galleryData de data nodig om naar de db te schrijven
     */
    private function createGalleryData($user_id)
    {
        $nameOfGallery = str_replace(' ', '_', $this->input->post('name'));
        $includedImages = $this->input->post('includedImages');

        $galleryData = 
        [ 
            'user_id' => $user_id,
            'gallery_name' => $nameOfGallery, 
            'gallery_included' => $includedImages,
            'gallery_created_at' => date('Y-m-d H:i:s') 
        ];

        return $galleryData;
    }

    /**
     * Verwijder een volledige galerij inclusief alle bestanden
     * @param $gallery_id
     */
    public function deleteGallery($gallery_id = NULL)
    {
        $this->loadLang(LOGS);
        if($gallery_id != NULL)
        {
            $folderName = $this->gallery_model->getGalleryFolderName($gallery_id); 
            $folderPath = DOCROOT . GALLERYS . $folderName . '/';

            if($this->gallery_model->removeDirectory($folderPath, $gallery_id))
            {
                $this->logging->Log($this->session->userdata('id'), '110', 'The gallery ' . $folderName . ' has been deleted');
                $this->session->set_tempdata('msg', $this->lang->line('log_110'), 100);
                $this->myRedirect();
            }
        }
        else
        {
            $this->logging->Log($this->session->userdata('id'), '120', 'No ID included for deletion of the gallery');
            $this->session->set_tempdata('error', $this->lang->line('log_120'), 100);
            $this->myRedirect();
        }

        $this->logging->Log($this->session->userdata('id'), '130', 'Could not delete gallery folder');
        $this->session->set_tempdata('error', $this->lang->line('log_130') . ' ' . $folderPath . ' ' . $gallery_id, 100);
        $this->myRedirect();
    }

    /**
     * Verwijder een foto
     * @param $image_name
     * @param $gallery_id
     */
    public function deleteImage($image_id, $gallery_id)
    {
        $image_name = $this->gallery_model->getImageName($image_id);
        $folderName = $this->gallery_model->getGalleryFolderName($gallery_id); 
        $folderPath = DOCROOT . GALLERYS . $folderName . '/';
        $fullOriginalPath = $folderPath . 'original/';

        $originalFile = $fullOriginalPath . $image_name;
        $watermarkFile = $folderPath . $image_name;

        $this->loadLang(LOGS);

        if(file_exists($originalFile) && file_exists($watermarkFile))
        {
            $originalDelete = $this->gallery_model->removeImage($originalFile, $image_id);
            $watermarkDelete = $this->gallery_model->removeImage($watermarkFile, $image_id);

            if($originalDelete && $watermarkDelete)
            {
                $this->logging->Log($this->session->userdata('id'), '140', 'image ' . $image_name . ' deleted out of gallery ' . $gallery_id);
                $this->session->set_tempdata('msg', $this->lang->line('log_140'), 100);
                redirect(base_url() . '/gallery/visitGallery/' . $gallery_id);
            }
        }

        $this->session->set_tempdata('error', 'Kon de foto niet verwijderen', 100);
        redirect(base_url() . '/gallery/visitGallery/' . $gallery_id);
    }
}
