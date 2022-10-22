<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * De settings van het systeem
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Setting extends MY_Controller 
{
	public function __construct() 
    {
		parent::__construct();
		$this->checkAcces(ADMIN);
		$this->load->model('setting_model');
		$this->loadLang(LOGS);
	}
	
	/**
     * Weergave van de settings pagina
     */
    public function index()
    {
        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_settings');
		$info['auth']  = 'admin';
		
		$data['settings'] = $this->setting_model->getAllSiteSettings();

		$this->loadLang(FORM);
		$this->loadLang(ADMIN_OVERVIEW);

        $this->load->view('templates/header', $info);
        $this->load->view('admin/admin_settings', $data);
        $this->load->view('templates/footer');
	}
	
	/**
	 * Pas de instellingen aan
	 */
	public function updateSiteSettings()
	{
		$settings = $this->setting_model->getAllSiteSettings();

		if($settings == NULL)
		{
			$this->session->set_tempdata('error', $this->lang->line('log_1180'), 100);
			$this->myRedirect();
		}

		foreach($settings as $setting)
		{			
			$setting_name = $this->input->post($setting['setting_name']);

			if($setting['setting_list_value'] == "1" || $setting['setting_list_value'] == "0")
			{
				$setting_list_value = (is_null($setting_name)) ? 0 : 1;
				if($setting_list_value != $setting['setting_list_value'])
				{
					$this->settings->updateSiteSetting($setting['setting_name'], $setting_list_value);
				}
			}
			else
			{
				$this->settings->updateSiteSetting($setting['setting_name'], $this->input->post($setting['setting_name']));
			}
		}
		
		$this->session->set_tempdata('msg', $this->lang->line('log_1130'), 100);
		$this->myRedirect();
	}

	/**
	 * Pas de persoonlijke instellingen aan van een gebruiker
	 */
	public function updatePersonalSettings($user_id)
	{
		$settings = $this->setting_model->getUserSettings($user_id);

		if($settings == NULL)
		{
			$this->session->set_tempdata('error', $this->lang->line('log_1190'), 100);
			$this->myRedirect();
		}

		$this->session->set_tempdata('msg', $this->lang->line('log_1140'), 100);
		$this->myRedirect();
	}
}
