<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings voor het gallerij systeem
 * Haal settings op of verander ze indien nodig
 */
class Settings 
{
	private $CI;

	/**
	 * Krijg een database instantie in de library
	 */
	function __construct() 
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
	}

	/**
	 * Update een site instelling
	 * 
	 * @param $setting_name
	 * @param $setting_list_value
	 */
	public function updateSiteSetting($setting_name, $setting_list_value)
	{
		$setting_id = $this->getSettingIdFromName($setting_name);

		if($setting_id != NULL)
		{
			$data = 
			[
				'setting_id' => $setting_id,
				'setting_list_site' => 1,
				'setting_list_value' => $setting_list_value,
				'setting_list_updated_at' => date('Y-m-d H:i:s')
			];

			$this->insertUpdateDatabase($data);
		}
		else
		{
			$this->CI->logging->Log('99999', '1110', 'Faulty setting_name ' . $setting_name . ' or setting_value ' . $setting_list_value . ' while setting value');
		}
	}

	/**
	 * Maak een persoonlijk instelling aan of pas deze aan
	 * 
	 * @param $user_id
	 * @param $setting_name
	 * @param $setting_list_value (mag leeg zijn)
	 */
	public function writePersonalSetting($user_id, $setting_name, $setting_list_value = 0)
	{
		$setting_id = $this->getSettingIdFromName($setting_name);

		if($setting_id != NULL && $user_id != NULL)
		{
			$data = 
			[
				'setting_id' => $setting_id,
				'user_id' => $user_id,
				'setting_list_site' => 0,
				'setting_list_value' => $setting_list_value,
				'setting_list_updated_at' => date('Y-m-d H:i:s')
			];

			$this->insertUpdateDatabase($data);
		}
		else
		{
			$this->CI->logging->Log('99999', '1110', 'Faulty personal setting_name ' . $setting_name . ' or setting_value ' . $setting_list_value . ' while setting value');
		}
	}

	/**
	 * Krijg de site value terug voor een bepaalde instelling
	 * 
	 * @param $setting_name
	 */
	public function getSiteValue($setting_name)
	{
		if($this->checkSettingName($setting_name))
		{
			return $this->getValueSetting($setting_name);
		}
		else
		{
			$this->CI->logging->Log('99999', '1120', 'Faulty setting_name ' . $setting_name . ' while getting value');
		}
	}

	/**
	 * Check if settings name is correctly inputed
	 *
	 * @param $setting_name
	 * @return true of false
	 */
	private function checkSettingName($setting_name)
	{
		$this->CI->db->select('setting_name');
		$settingNames = $this->CI->db->get('settings');
		$correctName = false;

		foreach($settingNames as $name)
		{
			if($name == $setting_name)
			{
				$correctName = true;
			}
		}

		return $correctName;
	}

	/**
	 * Schrijf de setting gegevens naar de database
	 * 
	 * @param $data data nodig voor het wegschrijven naar de db
	 */
	private function insertUpdateDatabase($data)
	{
		if($data['setting_list_site'] == 1)
		{
			$this->siteDatabaseSettings($data);
		}
		else
		{
			$this->personalDatabaseSettings($data);
		}
		
		$this->CI->logging->Log('99999', '1130', 'Settings Data correctly updated in db ' . $data['setting_id'] . ' ' . $data['setting_list_value']);
	}

	/**
	 * Schrijf de site instellingen weg
	 * 
	 * @param $data array voor database
	 */
	private function siteDatabaseSettings($data)
	{
		$this->CI->db->where('setting_id', $data['setting_id']);
		$this->CI->db->where('setting_list_site', $data['setting_list_site']);
		$query = $this->CI->db->get('settings_list');
		
		if($query->num_rows() > 0)
		{
			$this->CI->db->where('setting_id', $data['setting_id']);
			$this->CI->db->where('setting_list_site', $data['setting_list_site']);
			$this->CI->db->update('settings_list', $data);
		}
		else
		{
			$this->CI->db->insert('settings_list', $data);
		}
	}

	/**
	 * Schrijf persoonlijke instelling weg
	 * 
	 * @param $data array voor database
	 */
	private function personalDatabaseSettings($data)
	{
		$this->CI->db->where('setting_id', $data['setting_id']);
		$this->CI->db->where('user_id', $data['user_id']);
		$query = $this->CI->db->get('settings_list');

		if($query->num_rows() > 0)
		{
			$this->CI->db->where('setting_id', $data['setting_id']);
			$this->CI->db->where('user_id', $data['user_id']);
			$this->CI->db->update('settings_list', $data);
		}
		else
		{
			$this->CI->db->insert('settings_list', $data);
		}
	}

	/**
	 * Krijg de value terug van een instelling
	 * 
	 * @param $setting_name
	 * @param $user_id (mag leeg zijn)
	 * @return $setting_value
	 */
	private function getValueSetting($setting_name, $user_id = NULL)
	{
		$setting_id = $this->getSettingIdFromName($setting_name);

		if($setting_id == FALSE)
		{
			return '0';
		}

		$this->CI->db->select('setting_list_value');
		$this->CI->db->where('setting_id', $setting_id);
		if($user_id != NULL)
		{
			$this->CI->db->where('user_id', $user_id);
		}
		else
		{
			$this->CI->db->where('setting_list_site', 1);
		}

		$query = $this->CI->db->get('settings_list');

		return ($query->num_rows() > 0) ? $query->row()->setting_list_value : '0';
	}

	/**
	 * Krijg de id voor een setting naam terug
	 * 
	 * @param $setting_name
	 * @return $setting_id of false
	 */
	private function getSettingIdFromName($setting_name)
	{
		$this->CI->db->select('setting_id');
		$this->CI->db->where('setting_name', $setting_name);
		$query = $this->CI->db->get('settings');

		return ($query->num_rows() > 0) ? $query->row()->setting_id : FALSE;
	}
}
