<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller voor Calender
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.1
 */
class Calendar extends MY_Controller 
{
    public function __construct() 
    {
       parent::__construct();
       $this->checkAcces(ADMIN);
       $this->load->model('calendar_model');
    }
    
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index()
    {
        $info['title'] = 'Fotografie Sandy Admin Kalender';
        $info['auth']  = 'admin';
        $info['css_fullcalendar'] = TRUE;

        $this->loadLang(ADMIN_CALENDAR);

        $this->load->view('templates/header', $info);
        $this->load->view('admin/admin_calendar');
        $this->load->view('templates/footer');
    }

    /**
     * Geef de events terug
     */
    public function get_events()
    {
        $start = strtotime($this->input->get("start"));
        $end = strtotime($this->input->get("end"));

        $startdt = new DateTime('now'); // setup a local datetime
        $startdt->setTimestamp($start); // Set the date based on timestamp
        $start_format = $startdt->format('Y-m-d H:i:s');

        $enddt = new DateTime('now'); // setup a local datetime
        $enddt->setTimestamp($end); // Set the date based on timestamp
        $end_format = $enddt->format('Y-m-d H:i:s');

        $events = $this->calendar_model->get_events($start_format, $end_format);

        $data_events = array();

        foreach($events->result() as $event) {

            $data_events[] = array(
                "id" => $event->event_id,
                "title" => $event->event_title,
                "start" => $event->event_start,
                "end" => $event->event_end,
                "description" => $event->event_description
            );
        }

        // echo json_encode(array("events" => $data_events));
        echo json_encode($data_events);
        exit();
    }

    public function add_event()
    {
        $this->calendarValidation();
        $name = $this->input->post("name", TRUE);
        $desc = $this->input->post("description", TRUE);
        $date_time = $this->input->post("datetimes", TRUE);

        // Verander de standaard slash naar streepje
        // $date_time "2019-09-13 15:00 - 2019-09-13 16:00"
        $date_time = str_replace('/', '-', $date_time);
        $startEnd = explode(" - ", $date_time);

        $data = array(
            "event_title" => $name,
            "user_id" => $this->session->userdata('id'),
            "event_start" => $startEnd["0"],
            "event_end" => $startEnd["1"],
            "event_description" => $desc
        );

        if($this->calendar_model->add_event($data))
        {
            $this->index();
        }
        else
        {
            $this->loadLang(LOGS);
            $this->logging->Log($this->session->userdata('id'), '999', 'Problem with adding a event in the calendar');
            $this->session->set_tempdata('error',$this->lang->line('log_999'), 100);
            $this->myRedirect();
        }
    }

    /**
     * Validatie van een calender event
     */
    private function calendarValidation()
    {       
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('datetimes', 'Datetimes', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->session->set_tempdata('error', validation_errors(), 100);
            redirect(base_url() . 'calendar');
        }
    }
}
