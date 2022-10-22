<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het Galerij model voor de galerijen
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.1
 */
 
class Gallery_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }

    /** GETTERS / SETTERS **/

    /**
     * Krijg het aantal geselecteerde foto's terug van een specifieke galerij
     * @param $gallery_id
     * @return int van aantal geselecteerde foto's
     */
    public function getAmmountImagesSelected($gallery_id)
    {
        $this->db->select('COUNT(image_selected) as selected_ammount');
        $this->db->group_by('gallery_id'); 
        $this->db->from('images');
        $this->db->where('gallery_id', $gallery_id);
        $this->db->where('image_selected', TRUE);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? (int) $query->row()->selected_ammount : '0';
    }

    /**
     * Verkrijg de galerij naam
     * @param $gallery_id
     * @return $gallery_name
     */
    public function getGalleryName($gallery_id)
    {
        $this->db->select('gallery_name');
        $this->db->from('gallerys');
        $this->db->where('gallery_id', $gallery_id);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->gallery_name : FALSE;
    }

    /**
     * Verkrijg de unieke link terug
     * @param $gallery_id
     * @return $link_unique
     */
    public function getLinkUnique($gallery_id)
    {
        $this->db->select('link_unique');
        $this->db->from('links');
        $this->db->where('gallery_id', $gallery_id);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->link_unique : FALSE;
    }

    /**
     * Krijg alle galerijen van een gebruiker terug
     * @param $user_id
     * @return array van alle galerijen
     */
    public function getGallerys($user_id)
    {
        $this->db->select('*');
        $this->db->from('gallerys');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : NULL;
    }

    /**
     * kan een bezoeker de bepaalde foto zien
     * 
     * @param $link_unique unieke id van een gallery
     * @param $image_name de naam van de foto
     * 
     * @return TRUE of FALSE
     */
    public function canVisitorWatch($link_unique, $image_name)
    {
        $this->db->select('gallery_id');
        $this->db->from('links');
        $this->db->where('link_unique', $link_unique);

        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            $gallery_id = (int) $query->row()->gallery_id;
            $this->db->select('*');
            $this->db->from('images');
            $this->db->where('gallery_id', $gallery_id);
            $this->db->where('image_name', $image_name);

            $query = $this->db->get();

            return ($query->num_rows() > 0) ? TRUE : FALSE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Krijg de galerij folder naam terug
     * @param $gallery_id
     * @return string van de galerij naam
     */
    public function getGalleryFolderName($gallery_id)
    {
        $this->db->select('gallery_name');
        $this->db->from('gallerys');
        $this->db->where('gallery_id', $gallery_id);

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $galleryName = $query->row_array();
            return $gallery_id . '_' . $galleryName['gallery_name'];
        }

        return FALSE;
    }

    /**
     * Krijg alle foto info terug van een specifieke galerij
     * @param $gallery_id
     * @return array van alle foto's van een galerij
     */
    public function getGalleryImagesData($gallery_id)
    {
        $this->db->select('image_id, image_name, image_selected, image_locked, additional');
        $this->db->from('images');
        $this->db->where('gallery_id', $gallery_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    /**
     * Krijg het aantal foto's inclusief terug
     * @param $gallery_id
     * @return $gallery_included of false
     */
    public function getAmmountIncluded($gallery_id)
    {
        $this->db->select('gallery_included');
        $this->db->from('gallerys');
        $this->db->where('gallery_id', $gallery_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? (int) $query->row()->gallery_included : FALSE;
    }

    /**
     * Check if a gallery is downloaded
     * 
     * @param $gallery_id
     * @return true or false
     */
    public function isDownloaded($gallery_id)
    {
        $where = "gallery_downloaded is  NOT NULL";
        $this->db->select('gallery_downloaded');
        $this->db->from('gallerys');
        $this->db->where('gallery_id', $gallery_id);
        $this->db->where($where);

        $query = $this->db->get();

        // echo '<pre>';
        // var_dump(($query->num_rows() > 0) ? TRUE : FALSE);
        // echo '</pre>';
        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Krijg de bezoekers link terug
     * @param $gallery_id
     * @return NULL of bezoekers link
     */
    public function getVisitorLink($gallery_id)
    {
        if(!$this->getLinkUnique($gallery_id))
        {
            return NULL;
        }

        $link_unique = $this->getLinkUnique($gallery_id);

        return base_url() . 'visitor/link/' . $link_unique;
    }

    /**
     * Krijg de foto naam terug
     * @param $image_id
     * @return $image_name of false
     */
    public function getImageName($image_id)
    {
        $this->db->select('image_name');
        $this->db->from('images');
        $this->db->where('image_id', $image_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->image_name : FALSE;
    }

    /** GALLERY **/

    /**
     * Voeg een galerij toe aan de database
     * @param $galleryData
     * @return $insert_id
     */
    public function addGalleryToDB($galleryData)
    {
        $this->db->insert('gallerys', $galleryData);
        return $this->db->insert_id();
    }

    /**
     * Update de galerij data
     * @param $galleryData
     * @return success of fail
     */
    public function updateGallery($galleryData)
    {
        $this->db->where('gallery_id', $galleryData['gallery_id']);
        return $this->db->update('gallerys', $galleryData);
    }

    /**
     * Een folder toevoegen op basis van de aangemaakt db input id en de data
     * @param $galleryData de data toegevoegd aan de gallery database
     * @param $gallery_id de gallery_id van de net aangemaakte database rij
     * @return true of false
     */
    public function createGalleryFolder($galleryData, $gallery_id)
    {
        if(empty($gallery_id))
        {
            return false;
        }

        $galleryFolder = $gallery_id . '_' . $galleryData['gallery_name'];
        $newFolder = DOCROOT . 'gallerys/' . $galleryFolder;
        $originalFolder = $newFolder . '/original';
        $protectFolderFile = base_url() . 'assets/noacces/index.html';

        /**
         * Maak een folder aan met de galerij_id en naam
         * Plaats hierin een extra folder genaamd original
         * En voeg aan bijde folders een index.html toe voor toegang te beschermen
         */
        if(!is_dir($newFolder))
        {
            mkdir($newFolder,0755,TRUE);
            mkdir($originalFolder,0755,TRUE);
            copy($protectFolderFile, $newFolder . '/index.html');
            copy($protectFolderFile, $originalFolder . '/index.html');
            return true;
        }
        
        return false;
    }

    /**
     * Wie is de eigenaar van de galerij
     * @param $gallery_id
     * @return string met de naam van de eigenaar of false
     */
    public function whoOwnsGallery($gallery_id)
    {
        $this->db->select('users.user_firstname, users.user_name');
        $this->db->from('gallerys');
        $this->db->join('users', 'gallerys.user_id = users.user_id');
        $this->db->where('gallerys.gallery_id', $gallery_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }

    /** VISITOR **/

    /**
     * Aanmaken van een bezoekers link
     * @param $linkData array gallery_id en random alphanum 16 chars
     */
    public function createVisitorLink($linkData)
    {
        return $this->db->insert('links', $linkData);
    }

    /**
     * Verwijderen van een bezoekers link
     * @param $gallery_id
     */
    public function removeVisitorLink($gallery_id)
    {
        $this->db->where('gallery_id', $gallery_id);
        return $this->db->delete('links');
    }

    /** DELETE / REMOVE **/

    /**
     * Verwijder een foto met het exacte pad
     * @param $fullpath
     * @param $image_id
     * @return $deleted_image of false
     */
    public function removeImage($fullPath, $image_id)
    {
        return (unlink($fullPath)) ? $this->deleteImageDB($image_id) : FALSE;
    }

    /**
     * Verwijder een foto uit de database
     * @param $image_id
     * @return $delete image
     */
    private function deleteImageDB($image_id)
    {
        $this->db->where('image_id', $image_id);
        return $this->db->delete('images');
    }

    /**
     * Recursief verwijderen van een folder
     * @param $folderPath
     * @param $gallery_id
     */
    public function removeDirectory($folderPath, $gallery_id = null)
    {
        // vind alle files in de folder
        $files = glob($folderPath . '/*');
        
        foreach ($files as $file)
        {
            // indien het een folder is herhaal removeDirectory
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }

        $removedFolder = rmdir($folderPath);

        return ($removedFolder && $gallery_id != null) ? $this->removeGalleryDatabaseEntries($gallery_id) : FALSE;
    }

    /**
     * Verwijder alles gelinkt aan een galerij uit de database
     * @param $gallery_id
     * @return succes of fail
     */
    private function removeGalleryDatabaseEntries($gallery_id)
    {
        if($this->getVisitorLink($gallery_id))
        {
            $this->removeVisitorLink($gallery_id);
        }

        $this->deleteImagesDB($gallery_id);
        return $this->deleteGalleryDB($gallery_id);
    }

    /**
     * Verwijder alle images van een specifieke galerij uit de database
     * @param $gallery_id
     * @return succes of fail
     */
    private function deleteImagesDB($gallery_id)
    {
        $this->db->where('gallery_id', $gallery_id);
        return $this->db->delete('images');
    }

    /**
     * Verwijder een galerij uit de database
     * @param $gallery_id
     * @return succes of fail
     */
    private function deleteGalleryDB($gallery_id)
    {
        $this->db->where('gallery_id', $gallery_id);
        return $this->db->delete('gallerys');
    }
}
