<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Foto service model
 * Stuur een pad en upload data door voor het verwerken van de foto
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   0.1
 */

class Image_service extends MY_Service
{
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * Verklein een foto
     * @param $folderPath
     * @param $image_data de data van een upgeloade foto
     */
    public function resizeImage($folderPath, $image_data)
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
    public function watermarkImage($folderPath, $image_data)
    {
        $this->load->library('image_lib', '', 'watermark_lib');

        $config = array();
        $config['source_image'] = $folderPath . $image_data['file_name'];
        $config['image_library'] = 'gd2';
        $config['new_image'] = $image_data['file_name'];
        $config['wm_type'] = 'overlay';
        $config['wm_opacity'] = 50;
        $config['wm_overlay_path'] = 'assets/images/watermark.png';
        $config['wm_vrt_alignment'] = 'bottom';
        $config['wm_hor_alignment'] = 'right';
        $config['wm_padding'] = -25;

        $this->watermark_lib->initialize($config);

        return ($this->watermark_lib->watermark()) ? true : false;
    }
}