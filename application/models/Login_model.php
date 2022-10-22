<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Het Login model voor de data van de login te valideren
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.1
 */

class Login_model extends CI_Model
{
    /**
     * Controleer of email en paswoord overeenkomen in database
     * @param $user_email
     * @param $password
     * @return $user of NULL
     */
    public function checkLoginDetails($user_email,$password)
    {
        $user = $this->loadUserData($user_email);
        return ($this->checkPassword($user, $password)) ? $user : NULL;
    }

    /**
     * Controle van passwoord
     * @param $user de opgegeven gebruiker
     * @param $password het ingevulde paswoord
     * @return true,false aan de hand van de hash voor de paswoord controle
     */
    private function checkPassword($user, $password)
    {
        return (!empty($user['user_password'])) ? password_verify($password, $user['user_password']) : false;
    }

    /**
     * @param $user_email de gebruikers email
     * @return user de gebruiker uit de database
     */
    private function loadUserData($user_email)
    {
        $this->db->select('*');
        $this->db->where('user_email',$user_email);
        $query = $this->db->get('users', 1);

        return $query->row_array();
    }
}