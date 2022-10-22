<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het registreer model voor de data in de database te steken
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.1
 */

class Selection_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }

    /** GETTERS / SETTERS **/

    /**
     * Krijg alle geselecteerde foto's
     * @param $gallery_id
     * @return array van geselecteerde images of NULL
     */
    public function getAllSelectedImages($gallery_id)
    {
        $this->db->select('image_id');
        $this->db->from('images');
        $this->db->where('gallery_id', $gallery_id);
        $this->db->where('image_selected', '1');

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : NULL;
    }

    /**
     * Krijg alle niet vastgelegde geselecteerde foto's terug
     * @param $gallery_id
     * @return array van niet vastgelegde geselecteerde foto's
     */
    public function getAllNotLockedSelectedImages($gallery_id)
    {
        $this->db->select('image_id');
        $this->db->from('images');
        $this->db->where('gallery_id', $gallery_id);
        $this->db->where('image_selected', '1');
        $this->db->where('image_locked', '0');

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : NULL;
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
     * Update alle foto's
     * @param $imageData
     * @return success of fail
     */
    public function updateAllImages($imagesData)
    {
        return $this->db->update_batch('images', $imagesData, 'image_id');
    }

    /** IMAGE **/

    /**
     * Zie of een foto geselecteerd is
     * @param $image_id
     * @return true of false
     */
    public function isImageSelected($image_id)
    {
        $this->db->select('image_selected');
        $this->db->from('images');
        $this->db->where('image_id', $image_id);
        $query = $this->db->get();

        return ($query->row()->image_selected == 1) ? TRUE : FALSE;
    }

    /**
     * Zie of een foto vastgezet is
     * @param $image_id
     * @return true of false
     */
    public function isImageLocked($image_id)
    {
        $this->db->select('image_locked');
        $this->db->from('images');
        $this->db->where('image_id', $image_id);
        $query = $this->db->get();

        return ($query->row()->image_locked == 1) ? TRUE : FALSE;
    }

    /**
     * Lock alle geselecteerde foto's
     * @param $lockData een array van een image_id en locked status
     */
    public function lockSelectedImages($lockData)
    {
        // table, data array, where
        return $this->db->update_batch('images', $lockData, 'image_id');
    }

    /** SELECTION **/

    /**
     * Verander de selectie van een foto
     * @param $image_id
     * @param $is_selected
     */
    public function changeSelection($image_id, $is_selected)
    {
        $image_selected['image_selected'] = $is_selected;
        $this->db->where('image_id', $image_id);
        $this->db->update('images', $image_selected);
    }
}
