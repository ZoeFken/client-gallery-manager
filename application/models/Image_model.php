<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het Image upload model
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version 0.1
 */
 
class Image_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Voeg de foto data toe aan de database
     * @param $imageData de weg te schrijven data
     * @return true of false
     */
    public function addImageDataToDB($imageData)
    {
        return $this->db->insert('images', $imageData);
    }

    /**
     * Krijg de galerij id op basis van een image_id
     * @param $image_id
     * @return $gallery_id of NULL
     */
    public function getImageGalleryID($image_id)
    {
        $this->db->select('gallery_id');
        $this->db->from('images');
        $this->db->where('image_id', $image_id);
        
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->gallery_id : NULL;
    }
}