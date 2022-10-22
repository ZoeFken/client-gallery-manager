<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het User model, wegschrijven en editeren van de user data
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.5
 */

class User_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }

    /** GETTERS / SETTERS **/

    /**
     * Krijg één enkele user terug. Gebaseerd op een id
     * @param $user_id
     * @return $user user selected by the user_id of FALSE
     */ 
    public function getUserById($user_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }

    /**
     * Krijg de gebruiker zijn galerijen terug
     * @param $user_id
     * @return $array van de galerijen
     */
    public function getUserGallery($user_id)
    {
        $this->db->select('*');
        $this->db->from('gallerys');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row_array() : FALSE;
    }

    /**
     * Krijg de rechten van een enkele gebruiker
     * @param $user_id
     * @return $user user selected by the user_id
     */ 
    public function getUserAuth($user_id)
    {
        $this->db->select('user_auth');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? (int) $query->row()->user_auth : FALSE;
    }

    /**
     * Zet de gebruiker zijn nieuwe data
     * @param $databaseData
     * @return succes of fail
     */
    public function setUserData($databaseData)
    {
        $this->db->where('user_id', $databaseData['user_id']);
        return $this->db->update('users', $databaseData);
    }

    /**
     * Krijg alle adressen van een gebruiker terug
     * in het huidige systeem enkel een adres per gebruiker mogelijk
     * @param $user_id
     * @return een array van de geassocieerde adressen of één array met lege velden
     */
    public function getUserAddress($user_id)
    {
        $this->db->select('*');
        $this->db->from('address');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
           return $query->row_array();
        }
        else
        {
            return $data =
            [
                'address_street' => '',
                'address_number' => '',
                'address_appartment' => '',
                'address_postalcode' => '',
                'address_city' => '',
                'address_country' => '',
                'address_created_at' => ''
            ];
        }
    }

    /**
     * Krijg extra velden aan de hand van de rechten van de meegegeven gebruiker
     * @param $editableUser de gebruiker die zal geedit worden
     * @return $user de gebruiker plus extra rechten
     */ 
    public function getSelectedUserRights($editableUser)
    {
        $this->load->library('authorize', '', 'new_user');
        $this->new_user->setRights((int)$editableUser['user_auth']);

        $editableUser['klant'] = $this->new_user->checkAllow(KLANT);
        $editableUser['admin'] = $this->new_user->checkAllow(ADMIN);
        $editableUser['createadmin'] = $this->new_user->checkAllow(CREATEADMIN);
        
        return $editableUser;
    }

    /**
     * Krijg de gebruikers email aan de hand van de user_id
     * @param $user_id
     */
    public function getUserEmail($user_id)
    {
        $this->db->select('user_email');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->user_email : FALSE;
    }

    /** USER **/

    /**
     * Voeg een gebruiker toe aan de database
     * @param $data de gebruikers data
     * @return $insert_id of FALSE
     */
    public function addUser($data)
    {
        return ($this->db->insert('users', $data)) ? $this->db->insert_id() : FALSE;
    }

    /**
     * update data van een specifieke gebruiker
     * @param $databaseData de data om weg te schrijven
     * @return succes of fail
     */
    public function editUser($databaseData)
    {
        $this->db->where('user_id', $databaseData['user_id']);
        return $this->db->update('users', $databaseData);
    }

    /**
     * Verwijder een gebruiker uit de database
     * @param $user_id
     * @return succes of fail
     */
    public function removeUser($user_id)
    {
        if($this->userHasAddress($user_id))
        {
            $this->deleteAddressByUserId($user_id);
        }

        $user_email = $this->getUserEmail($user_id);
        if($this->userHasActivationLink($user_email))
        {
            $this->deleteActivationLink($user_email);
        }
        
        $this->db->where('user_id', $user_id);
        return $this->db->delete('users');
    }

    /**
     * Heeft de gebruiker een reset link
     * @param $user_email
     */
    private function userHasActivationLink($user_email)
    {
        $this->db->select('*');
        $this->db->from('resets');
        $this->db->where('user_email', $user_email);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Verwijder de activatie link
     */
    private function deleteActivationLink($user_email)
    {
        $this->db->where('user_email', $user_email);
        return $this->db->delete('resets');
    }

    /**
     * Krijg de reset link
     */
    public function getUserResetLink($user_email)
    {
        $this->db->select('*');
        $this->db->from('resets');
        $this->db->where('user_email', $user_email);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->reset_token : FALSE;
    }

    /**
     * Kijk of een gebruiker de eigenaar is van een galerij
     * @param $gallery_id
     * @param $user_id
     * @return true of false
     */
    public function isUserOwnerGallery($gallery_id, $user_id)
    {
        $this->db->select('user_id');
        $this->db->from('gallerys');
        $this->db->where('user_id', $user_id);
        $this->db->where('gallery_id', $gallery_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Controle of the gebruiker een klant is
     * @param $user_id een gebruikers id
     */
    public function isClient($user_id)
    {
        $userById = $this->getUserById($user_id);

        if($userById != NULL)
        {
            $this->load->library('authorize', '', 'new_user');
            $this->new_user->setRights((int)$userById['user_auth']);
            
            return ($this->new_user->checkAllow(KLANT)) ? TRUE : FALSE;
        }

        return FALSE;
    }

    /**
     * Controleer of de link toegang geeft tot een gallerij
     * @param $link_unique unieke code
     * @return $gallery_id of false
     */
    public function isVisitor($link_unique)
    {
        $this->db->select('gallery_id');
        $this->db->from('links');
        $this->db->where('link_unique', $link_unique);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->gallery_id : FALSE;
    }

    /** ADRES **/

    /**
     * Voeg een adres toe aan de database
     * @param $data de adres data
     * @return succes of fail
     */
    public function addAddress($data)
    {
        return $this->db->insert('address', $data);
    }

    /**
     * Update data van een specifieke gebruiker
     * @param $databaseData de aangepaste adres data om weg te schrijven
     * @return succes of fail
     */
    public function editAddress($databaseData)
    {
        $this->db->where('user_id', $databaseData['user_id']);
        return $this->db->update('address', $databaseData);
    }

    /**
     * Heeft de gebruiker een adres
     * @param $user_id
     * @return TRUE of FALSE
     */
    public function userHasAddress($user_id)
    {
        $this->db->select('user_id');
        $this->db->from('address');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }
    
    /**
     * Verwijder een adres op basis van een user id
     * @param $user_id
     * @return succes of fail
     */
    public function deleteAddressByUserId($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('address');
    }

    /** EMAIL UNIQUE USER **/

    /**
     * Controle of een gebruiker unique is
     * @param $user_id
     * @param $user_email email ingevuld in het edit formulier
     * @return true of false
     */
    public function doesEmailExistOtherUser($user_id, $user_email)
    {
        $idOrFalse = $this->emailExistsGetIdOrFalse($user_email);

        if($idOrFalse === FALSE)
        {
            return true;
        }

        return ($idOrFalse == $user_id) ? true : false;
    }

    /**
     * Bestaad de email reeds in de database
     * @param $email de te controleren email
     * @return $id of false
     */
    private function emailExistsGetIdOrFalse($email)
    {
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('user_email', $email);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->user_id : FALSE;
    }
}
