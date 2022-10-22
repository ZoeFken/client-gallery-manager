<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het download model
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1
 */

class Download_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
	}

	/**
     * Krijg alle vastgelegde foto's van een specifieke galerij
     * @param $gallery_id
     * @return Een lijst van vastgelegde foto's of NULL
     */
    public function getLockedImages($gallery_id)
    {
        $this->db->select('*');
        $this->db->from('images');
        $this->db->where('gallery_id', $gallery_id);
        $this->db->where('image_locked', '1');

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : NULL;
    }

    /**
     * Zet de galerij op gedownload
     * 
     * @param $gallery_id
     */
    public function setDownloaded($gallery_id)
    {
        $gallery_downloaded['gallery_downloaded'] = '1';
        $this->db->where('gallery_id', $gallery_id);
        $this->db->update('gallerys', $gallery_downloaded);
    }
}
