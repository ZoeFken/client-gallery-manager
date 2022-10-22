<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het weergeven van de foto's met header
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.2
 */

class Img extends MY_Controller 
{
    /**
     * Stuur een image locatie door buiten de root
     * @param $file de naam van de foto
     * @param $foldername de naam van de folder
     * @param $link de unieke link voor een bezoeker
     * @return het path en correcte header
     */
    public function jpg($foldername, $file, $link = NULL)
    {
        $this->load->model('gallery_model');

        if($link == NULL)
        {
            if (!($this->checkAcces(KLANT, FALSE) || $this->checkAcces(ADMIN, FALSE)))
            {
                $this->myRedirect();
            }
        }
        elseif(!$this->gallery_model->canVisitorWatch($link, $file))
        {
            $this->myRedirect();
        }


        if(($foldername != null) && ($file != null))
        {
            $filename = basename($file);
            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            switch($file_extension) 
            {
                case "gif": $contentType="image/gif"; break;
                case "png": $contentType="image/png"; break;
                case "jpeg": $contentType="image/jpeg"; break;
                case "jpg": $contentType="image/jpeg"; break;
                default:
            }

            $path = DOCROOT . GALLERYS . $foldername . '/' . $file;
            header('Content-type: ' . $contentType);
            readfile($path);
        }      
    }
}
