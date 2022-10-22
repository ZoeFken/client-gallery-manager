<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het uploaden van images
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Image_upload extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gallery_model');
        $this->checkAcces(ADMIN);
    }
    
    /**
     * Genereer de upload pagina
     * @param $gallery_id de galerij waarnaartoe de foto's moeten
     */
    public function upload_page($gallery_id)
    {
        $info['title'] = 'Foto Upload';
        $info['auth']  = 'admin';
        $info['css_dropzone'] = TRUE;

        $data['gallery_id'] = $gallery_id;

        $this->load->view('templates/header', $info);
        $this->load->view('admin/image_upload', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Het uploaden, resizen en watermerken van een file naar een specifieke galerij
     * Originele file wordt opgeslagen en de aangepaste
     * @param $gallery_id een specifieke galerij id
     */
    public function fileUpload($gallery_id)
    {
        if(!empty($gallery_id) && !empty($_FILES['file']['name']))
        {
            $folderName = $this->gallery_model->getGalleryFolderName($gallery_id); 

            $folderPath = DOCROOT . GALLERYS . $folderName . '/';
            $fullOriginalPath = $folderPath . 'original';
            $name = $this->rename($fullOriginalPath, $_FILES['file']['name']);

            // Originele uploaden
            $config['upload_path'] =  $fullOriginalPath;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $name;

            $this->load->library('upload',$config);            

            if(!$this->upload->do_upload('file'))
            {
                $this->logging->Log($this->session->userdata('id'), '210', 'image ' . $name . ' failed to upload the image');
                $this->output->set_header("HTTP/1.0 400 Bad Request");
                echo "error bij uploaden";
            }

            $image_data = array();
            $image_data = $this->upload->data();

            $this->load->service('image_service');

            if(!$this->image_service->resizeImage($folderPath, $image_data))
            {
                $this->session->set_tempdata('error', 'Er is een probleem met het verkleinen', 100);
                $this->logging->Log($this->session->userdata('id'), '250', 'image ' . $name . ' failed to resize the image');
                $this->myRedirect();  
            }

            if(!$this->image_service->watermarkImage($folderPath, $image_data))
            {
                $this->session->set_tempdata('error', 'Er is een probleem met het watermerken', 100);
                $this->logging->Log($this->session->userdata('id'), '240', 'image ' . $name . ' failed to add a watermark');
                $this->myRedirect();
            }

            $name = str_replace(' ', '_', $name);
            $imageDatabaseData = 
            [
                'gallery_id' => $gallery_id,
                'image_name' => $name,
                'image_created_at' => date('Y-m-d H:i:s') 
            ];

            $this->load->model('image_model');
            if(!$this->image_model->addImageDataToDB($imageDatabaseData))
            {
                $this->session->set_tempdata('error', 'Het bestand kon niet worden toegevoegd aan de db', 100);
                $this->logging->Log($this->session->userdata('id'), '230', 'image ' . $name . ' failed to add to database ');
                $this->myRedirect();
            }

            $this->logging->Log($this->session->userdata('id'), '220', 'image ' . $name . ' added to gallery ' . $gallery_id);
        }
    }

    /**
     * Controlleer of de naam van een bestand reeds bestaad in een map,
     * indien zo hernoem de file
     * @param $fullOriginalPath het pad naar de map van de originele files
     * @param $name de naam van het bestand
     */
    private function rename($fullOriginalPath, $name)
    {
        // verander alle spaties naar underscores
        $newName = str_replace(' ', '_', $name);

        $actualName = pathinfo($newName, PATHINFO_FILENAME);
        $originalName = $actualName; // als backup
        $extension = pathinfo($newName, PATHINFO_EXTENSION);
        $addon = 1;
        
        while (file_exists($fullOriginalPath . '/' . $actualName . '.' . $extension))
        {
            $actualName = (string)$originalName . '_' . $addon;
            $newName = $actualName . '.' . $extension;
            $addon++;
        }

        if($newName != $name)
        {
            $this->logging->Log($this->session->userdata('id'), '260', 'image name ' . $name . ' changed to ' . $newName);
        }
        
        return $newName;
    }
}