<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het registreer model voor de data in de database te steken
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.2
 */

class Activation_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * Genereer een random string
     * @return $key een random string
     */
    public function createRandomString()
    {
        $this->load->helper('string');
        return random_string('alnum', 16);
    }

    /**
     * Krijg één enkele user terug. Gebaseerd op een id
     * @param $user_id
     * @return De geselecteerde gebruiker of NULL
     */ 
    public function getUserById($user_id)
    {
        $this->db->select('user_email, user_name, user_firstname, user_auth');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row_array() : NULL;
    }

    /**
     * Steek de gegenereerde key in de database
     * @param $data de data voor insert in database
     * @return true of false
     */
    public function insertResetKey($data)
    {
        return $this->db->insert('resets', $data);
    }

    /**
     * Verander de reset key in de database
     * @param $data voor aan te passen in de database
     * @return true of false
     */
    public function replaceResetKey($data)
    {
        return $this->db->replace('resets', $data);
    }

    /**
     * update data van een specifieke gebruiker
     * @param $databaseData de data om weg te schrijven
     * @return true of false
     */
    public function updateLoginRightDB($user)
    {
        $rights['user_auth'] = $user['user_auth'];

        $this->db->where('user_email', $user['user_email']);
        return $this->db->update('users', $rights);
    }

    /**
     * Voeg de login rechten toe aan de user indien nodig
     * @param $user de geselecteerde gebruiker
     * @return $user de aangepaste gebruiker met de login rechten
     */
    public function grantLogin($user)
    {
        $this->load->library('authorize', '', 'update_authorize');
        $this->update_authorize->setRights((int)$user['user_auth']);
        if(!$this->update_authorize->checkAllow(LOGIN))
        {
            $this->update_authorize->grant(LOGIN);
        }
        
        $user['user_auth'] = $this->update_authorize->getRightsDecimal();

        return $user;
    }

    /**
     * Controle of reset email reeds in resets db zit
     * @param $email specifieke gebruikers email
     * @return true of false
     */
    public function checkResetEmail($email)
    {
        $this->db->select('user_email');
        $this->db->from('resets');
        $this->db->where('user_email', $email);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? true : false;
    }
}