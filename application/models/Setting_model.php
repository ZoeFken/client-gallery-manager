<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het Instellingen model
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1
 */

class Setting_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
	}

	/**
	 * Krijg alle instellingen terug
	 * 
	 * @return array met alle instellingen
	 */
	public function getAllSiteSettings()
	{
		$this->db->select('*');
		$this->db->from('settings_list');
		$this->db->join('settings', 'settings_list.setting_id = settings.setting_id');
		$this->db->where('setting_list_site', 1);
		
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->result_array() : NULL;
	}

	/**
	 * Krijg alle instellingen van een gebruiker terug
	 */
	public function getUserSettings($user_id)
	{
		$this->db->select('*');
		$this->db->from('settings_list');
		$this->db->join('settings', 'settings_list.setting_id = settings.setting_id');
		$this->db->where('user_id', $user_id);
		
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->result_array() : NULL;
	}
}
