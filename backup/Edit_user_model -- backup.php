<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het edit user model voor de data in de database te editeren
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   0.1
 */

class Edit_user_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * update data van een specifieke gebruiker
     * @param $databaseData de data om weg te schrijven
     */
    public function edit_user($databaseData)
    {
        $this->db->where('user_id', $databaseData['user_id']);
        return $this->db->update('users', $databaseData);
    }

    /**
     * Krijg één enkele user terug. Gebaseerd op een id
     * @param $id de ID van een specifieke gebruiker
     * @return $user user selected by the user_id
     */ 
    public function getUserById($id)
    {
        $this->db->select('user_id, user_email, user_name, user_firstname, user_adres, user_postalcode, user_city, user_telephone, user_auth');
        $this->db->from('users');
        $this->db->where('user_id', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
           return $query->row_array();
        }
    }

    /**
     * Controle of email uniek is
     * @param $id de ID van een specifieke gebruiker
     * @param $email email ingevuld in het edit formulier
     * @return true of false
     */
    public function isUniqueUser($id, $email)
    {
        $idOrFalse = $this->emailExistsGetIdOrFalse($email);

        if($idOrFalse === false)
        {
            return true;
        }

        return ($idOrFalse == $id) ? true : false;
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

        if ($query->num_rows() > 0)
        {
            $user = $query->row_array();
            return $user['user_id'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Krijg extra velden aan de hand van de rechten van de meegegeven gebruiker
     * @param $editableUser de gebruiker die zal geedit worden
     * @return $user de gebruiker plus extra rechten
     */ 
    public function getSelectedRights($editableUser)
    {
        $this->load->library('authorize', '', 'new_user');
        $this->new_user->setRights((int)$editableUser['user_auth']);

        $editableUser['klant'] = ($this->new_user->checkAllow(KLANT)) ? true : false;
        $editableUser['admin'] = ($this->new_user->checkAllow(ADMIN)) ? true : false;
        $editableUser['createadmin'] = ($this->new_user->checkAllow(CREATEADMIN)) ? true : false;
        
        return $editableUser;
    }
}