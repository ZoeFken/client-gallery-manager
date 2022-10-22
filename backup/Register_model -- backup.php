<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het registreer model voor de data in de database te steken
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   0.1
 */

class Register_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }
    /**
     * Voeg een user toe aan de database
     */
    public function add_user($data)
    {
        if($this->db->insert('users', $data))
        {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function add_address($data)
    {
        return $this->db->insert('address', $data);
    }

    /**
     * Krijg een gebruiker terug op basis van de private key
     * @param $key een private key
     */
    public function getUser($key)
    {
        $userErmail = $this->getEmail($key);
    }

    /**
     * Controller de key en krijg de geassocieerde email terug
     * @param $key private key
     * @return user_email
     */
    public function getEmail($key)
    {
        $this->db->select('user_email');
        $this->db->from('resets');
        $this->db->where('reset_token', $key);

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
           $var = $query->row_array();
           return $var['user_email'];
        }
    }
}