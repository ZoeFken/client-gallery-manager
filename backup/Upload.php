<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Een library voor de upload en resize en watermark
 */
class Upload
{
    public function configResize()
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['new_image'] = $fullPath . $image_data['file_name'];
        $config['maintain_ratio'] = TRUE;
        $config['quality'] = 100;
        $config['width'] = 750;
        $config['height'] = 750;

        return $config;
    }

    public function configWatermark()
    {
        $config = array();
        $config['source_image'] = $fullPath . $image_data['file_name'];
        $config['image_library'] = 'gd2';
        $config['new_image'] = $image_data['file_name'];
        $config['wm_type'] = 'overlay';
        $config['wm_opacity'] = 50;
        $config['wm_overlay_path'] = 'assets/images/watermark.png';
        $config['wm_vrt_alignment'] = 'bottom';
        $config['wm_hor_alignment'] = 'right';
        $config['wm_padding'] = -25;

        return $config;
    }

    public function configUpload()
    {
        $config['upload_path'] =  $fullOriginalPath;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $_FILES['file']['name'];

        return $config;
    }
}