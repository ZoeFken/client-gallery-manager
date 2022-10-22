<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
        if (!empty($gallery_id) && !empty($_FILES['file']['name'])) {
            $folderName = $this->gallery_model->getGalleryFolderName($gallery_id);

            $folderPath = DOCROOT . GALLERYS . $folderName . '/';
            $fullOriginalPath = $folderPath . 'original';
            $name = $this->rename($fullOriginalPath, $_FILES['file']['name']);

            // Originele uploaden
            $config['upload_path'] =  $fullOriginalPath;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $name;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                $this->logging->Log($this->session->userdata('id'), '210', 'image ' . $name . ' failed to upload the image');
                $this->output->set_header("HTTP/1.0 400 Bad Request");
                echo "error bij uploaden";
            }

            $this->loadLang(LOGS);
            $image_data = array();
            $image_data = $this->upload->data();

            if (!$this->resizeImage($folderPath, $image_data)) {
                $this->logging->Log($this->session->userdata('id'), '250', 'image ' . $name . ' failed to resize the image');
                $this->session->set_tempdata('error', $this->lang->line('log_260'), 100);
                $this->myRedirect();
            }

            if (!$this->watermarkImage($folderPath, $image_data)) {
                $this->logging->Log($this->session->userdata('id'), '240', 'image ' . $name . ' failed to add a watermark');
                $this->session->set_tempdata('error', $this->lang->line('log_240'), 100);
                $this->myRedirect();
            }

            $name = str_replace(' ', '_', $name);
            // Het verkrijgen van de grote van de foto
            list($width, $height) = getImagesize($folderPath . '/' . $name);
            $imageData = array(
                'width' => $width,
                'height' => $height
            );

            $imageDatabaseData =
                [
                    'gallery_id' => $gallery_id,
                    'image_name' => $name,
                    'additional' => serialize($imageData),
                    'image_created_at' => date('Y-m-d H:i:s')
                ];

            $this->load->model('image_model');
            if (!$this->image_model->addImageDataToDB($imageDatabaseData)) {
                $this->logging->Log($this->session->userdata('id'), '230', 'image ' . $name . ' failed to add to database ');
                $this->session->set_tempdata('error', $this->lang->line('log_230'), 100);
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

        while (file_exists($fullOriginalPath . '/' . $actualName . '.' . $extension)) {
            $actualName = (string)$originalName . '_' . $addon;
            $newName = $actualName . '.' . $extension;
            $addon++;
        }

        if ($newName != $name) {
            $this->logging->Log($this->session->userdata('id'), '260', 'image name ' . $name . ' changed to ' . $newName);
        }

        return $newName;
    }

    /**
     * Verklein een foto
     * @param $folderPath
     * @param $image_data de data van een upgeloade foto
     */
    private function resizeImage($folderPath, $image_data)
    {
        $this->load->library('image_lib', '', 'resize_lib');

        $config = array();
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['new_image'] = $folderPath . $image_data['file_name'];
        $config['maintain_ratio'] = TRUE;
        $config['quality'] = 100;
        $config['width'] = 750;
        $config['height'] = 750;

        $this->resize_lib->initialize($config);

        return ($this->resize_lib->resize()) ? true : false;
    }

    /**
     * Voorzie een foto met een watermerk
     * @param $folderPath
     * @param $image_data de data van een upgeloade foto
     */
    private function watermarkImage($folderPath, $image_data)
    {
        $this->load->library('image_lib', '', 'watermark_lib');

        $config = array();
        $config['source_image'] = $folderPath . $image_data['file_name'];
        $config['image_library'] = 'gd2';
        $config['new_image'] = $image_data['file_name'];
        $config['wm_type'] = 'overlay';
        $config['wm_opacity'] = 50;
        $config['wm_overlay_path'] = 'assets/images/watermark1.png';
        $config['wm_vrt_alignment'] = 'bottom';
        $config['wm_hor_alignment'] = 'right';
        $config['wm_padding'] = -25;

        // $config = array();
        // $config['source_image'] = $folderPath . $image_data['file_name'];
        // $config['image_library'] = 'gd2';
        // $config['new_image'] = $image_data['file_name'];
        // $config['wm_type'] = 'overlay';
        // $config['wm_opacity'] = 25;
        // $config['wm_overlay_path'] = 'assets/images/watermark1.png';
        // $config['wm_vrt_alignment'] = 'middle';
        // $config['wm_hor_alignment'] = 'center';

        $this->watermark_lib->initialize($config);

        return ($this->watermark_lib->watermark()) ? true : false;
    }
}
