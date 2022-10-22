<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het registreer model voor de data in de database te steken
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.2
 */

class Calendar_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Krijg events terug binnen twee parameters
     * 
     * @param $event_start de start datum en tijd
     * @param $event_end de eind datum en tijd
     */
    public function get_events($event_start, $event_end)
    {
        return $this->db->where("event_start >=", $event_start)->where("event_end <=", $event_end)->get("events");
    }

    /**
     * Voeg een event toe
     * 
     * @param $date de data nodig voor het event toe te voegen
     */
    public function add_event($data)
    {
        return $this->db->insert("events", $data);
    }

    /**
     * Krijg één event terug
     * 
     * @param $event_id de event_id
     */
    public function get_event($event_id)
    {
        return $this->db->where("event_id", $event_id)->get("events");
    }

    /**
     * Update een event
     * 
     * @param $event_id de event id
     * @param $data de data nodig voor het event
     */
    public function update_event($event_id, $data)
    {
        $this->db->where("event_id", $event_id)->update("events", $data);
    }

    /**
     * Verwijder een event
     * 
     * @param $event_id de event id
     */
    public function delete_event($event_id)
    {
        $this->db->where("event_id", $event_id)->delete("events");
    }
}