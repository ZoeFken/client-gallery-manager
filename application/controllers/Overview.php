<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor de overview van de admin
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Overview extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->checkAcces(ADMIN);
        $this->load->model('overview_model');
        $this->load->library('session');
    }

    /**
     * Geef de klanten weer
     */
    public function index()
    {
        $location = $this->session->userdata('location');
        switch ($location) {
            case 'active':
                $this->active();
                break;
            case 'admin':
                $this->admin();
                break;
            case 'inactive':
                $this->inactive();
                break;
            default:
                $this->active();
        }
    }

    /**
     * Krijg enkel de actieve klanten
     */
    public function active()
    {
        $location = array('location' => 'active');
        $this->session->set_userdata($location);
        $users['users'] = $this->overview_model->get_klanten();
        $this->loadOverview($users, 'active');
    }

    /**
     * Krijg enkel de admins
     */
    public function admin()
    {
        $location = array('location' => 'admin');
        $this->session->set_userdata($location);
        $users['users'] = $this->overview_model->get_admins();
        $this->loadOverview($users, 'admin');
    }

    /**
     * Krijg enkel de inactive klanten
     */
    public function inactive()
    {
        $location = array('location' => 'inactive');
        $this->session->set_userdata($location);
        $users['users'] = $this->overview_model->get_inactiveUsers();
        $this->loadOverview($users, 'inactive');
    }

    /**
     * Laad de pagina met de bijgeleverde users
     * @param $users de database array
     * @param $type het type van gebruikers
     */
    private function loadOverview($users, $type)
    {
        // Creeër een flash session indien er geen gebruikers zijn.
        if($users === NULL)
        {
            echo $this->session->set_tempdata('msg','Geen gebruikers', 100);
        }

        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_overview');
        $info['auth'] = 'admin';
        $info['type'] = $type;

        $this->loadLang(FORM);
        $this->loadLang(ADMIN_OVERVIEW);

        $this->load->view('templates/header', $info);
        $this->load->view('admin/overview', $users);
        $this->load->view('templates/footer');
	}
	
	/**
	 * Export de logs in CSV formaat
	 */
	public function exportLogs()
	{
		$filename = 'logs_'.date('Ymd').'.csv'; 
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$filename"); 
		header("Content-Type: application/csv; ");
		
		$logs = $this->overview_model->getCSVData();

		// echo "<pre>";
		// var_dump($logs);
		// echo "</pre>";

		$file = fopen('php://output', 'w');
	  
		$header = array("log_id", "user_id", "log_code", "log_message", "log_created_at", "user_name", "user_firstname", "user_email"); 
		fputcsv($file, $header);
		foreach ($logs as $key=>$line){ 
		  fputcsv($file,$line); 
		}
		fclose($file); 
		exit; 
	}
}
