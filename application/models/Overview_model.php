<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Het Overview model voor de data van gebruikers weer te geven
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.5
 */

class Overview_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * krijg alle actieve klanten terug
     */
    public function get_klanten()
    {
        $level = 'client';
        return $this->getGroupOfUsers(KLANT);
    }

    /**
     * krijg alle actieve administrators terug
     */
    public function get_admins()
    {
        $level = 'admin';
        return $this->getGroupOfUsers(ADMIN);
    }

    /**
     * krijg alle inactieve gebruikers terug
     */
    public function get_inactiveUsers()
    {
        $level = 'inactive';
        return $this->getGroupOfUsers(LOGIN);
    }

    /**
     * Krijg een specifieke user group en voeg indien aanwezig de geassocieerde galerij(en) eraan toe
     * @param $level de level nodig voor de groep vorming
     * @return $group de gevormde group
     */
    private function getGroupOfUsers($level = LOGIN)
    {
        $this->load->library('authorize', '', 'temp_authorize');

        $users = $this->getAllUsers();
        $gallerys = $this->getAllGallerys();
        $group = array();

        // Nog is kijken voor een refactor
        foreach ($users as $user) {
            // als er galerijen zijn voor een gebruiker
            if (!empty($gallerys)) {
                foreach ($gallerys as $gallery) {
                    if ($gallery['user_id'] === $user['user_id']) {
                        $user['gallery'][$gallery['gallery_id']] = $gallery;
                        $user['gallery'][$gallery['gallery_id']]['ammount_selected'] = $this->getAmmountImagesSelected($gallery['gallery_id']);
                    }
                }
            }

            $this->temp_authorize->setRights((int)$user['user_auth']);

            // kijk voor inactief of andere level
            if ($level != LOGIN && $this->temp_authorize->checkAllow($level) && $this->temp_authorize->checkAllow(LOGIN)) {
                $user['level'] = $level;
                array_push($group, $user);
            } elseif ($level == LOGIN && !$this->temp_authorize->checkAllow(LOGIN)) {
                $user['level'] = $level;
                array_push($group, $user);
            }
        }

        return $group;
    }

    /**
     * Overview query krijg alle gebruikers terug.
     * @return Alle gebruikers uit de database of NULL
     */
    private function getAllUsers()
    {
        $this->db->select('user_id, user_email, user_name, user_firstname, user_auth');
        $this->db->from('users');

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : NULL;
    }

    /**
     * Krijg alle logs terug
     * @return array van logs
     */
    private function getAllLogs()
    {
        $this->db->select('*');
        $this->db->from('logs');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : NULL;
    }

    /**
     * Overview query krijg alle galerijen terug.
     * @return Alle galerijen uit de database of NULL
     */
    private function getAllGallerys()
    {
        $this->db->select('*');
        $this->db->from('gallerys');

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result_array() : NULL;
    }

    /**
     * Krijg het aantal geselecteerde foto's terug van een specifieke galerij
     * @param $gallery_id
     * @return int van aantal geselecteerde foto's indien geen int 0
     */
    public function getAmmountImagesSelected($gallery_id)
    {
        $this->db->select('COUNT(image_selected) as selected_ammount');
        $this->db->group_by('gallery_id');
        $this->db->from('images');
        $this->db->where('gallery_id', $gallery_id);
        $this->db->where('image_selected', true);

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? (int) $query->row()->selected_ammount : (int) 0;
    }

    /**
     * Krijg alle data terug voor de CSV
     * Opgemaakt door alle gebruikers en logs te combineren
     * Niet mogelijk door een join te gebruiken omdat gebruikers kunnen verwijderd worden
     * @return array van logs
     */
    public function getCSVData()
    {
        $users = $this->getAllUsers();
        $logs = $this->getAllLogs();
        $newLog = array();

        foreach ($logs as $log) {
            $user_id = $log['user_id'];
            foreach ($users as $user) {
                if ($user_id == $user['user_id']) {
                    $log['user_firstname'] = $user['user_firstname'];
                    $log['user_name'] = $user['user_name'];
                    $log['user_email'] = $user['user_email'];
                    break;
                }
            }
            if (!array_key_exists('user_name', $log)) {
                $log['user_firstname'] = 'No Firstname';
                $log['user_name'] = 'No Name';
                $log['user_email'] = 'No Email';
            }
            $newLog[] = $log;
        }

        return $newLog;
    }
}
