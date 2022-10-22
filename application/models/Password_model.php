<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het registreer model voor de data in de database te steken
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.1
 */

class Password_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * update paswoord data van een specifieke gebruiker
     * @param $passwordData de data om weg te schrijven
     */
    public function updatePassword($passwordData)
    {
        $this->db->where('user_email', $passwordData['user_email']);
        return $this->db->update('users', $passwordData);
    }

    /**
     * delete de specifieke gegeven voor registratie in de db
     * @param $email de email die toelating gaf om een psw te registreren
     */
    public function deleteResetEntry($user_email)
    {
        $this->db->where('user_email', $user_email);
        return $this->db->delete('resets'); 
    }

    /**
     * Controller de key en krijg de geassocieerde email terug
     * @param $key private key
     * @return user_email
     */
    public function getEmailThroughKey($key)
    {
        $this->db->select('user_email');
        $this->db->from('resets');
        $this->db->where('reset_token', $key);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->row()->user_email : FALSE;
    }

    /**
     * Controle of het ingegeven paswoord overeenkomt met dat in de database
     * @param $user_email
     * @param $oldPassword
     */
    public function checkPassword($user_email, $oldPassword)
    {
        $this->db->select('user_password');
        $this->db->from('users');
        $this->db->where('user_email', $user_email);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? password_verify($oldPassword, $query->row()->user_password) : FALSE;
    }
}