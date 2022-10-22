<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Database logging
 * Schrijf een log bij elke belangrijke actie in het systeem
 */
class Logging
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
     * Genereer de array log en schrijf deze weg
     * @param $user_id
     * @param $log_code
     * @param $log_message
     * @param $log_error
     */
    public function Log($user_id, $log_code, $log_message)
    {
        $data = 
        [
            'user_id' => $user_id,
            'log_code' => $log_code,
            'log_message' => $log_message,
            'log_created_at' => date('Y-m-d H:i:s')
        ];

        $this->checkArrayLog($data);
    }

    /**
     * Controleer de array log
     * @param $array :: bestaande uit $user_id, $log_code, $log_message, $log_error
     */
    private function checkArrayLog($array)
    {
        $error = array();
        foreach($array as $key => $value)
        {
            if(is_null($value))
            {
                $this->logError();
            }
        }

        $this->writeLog($array);
    }

    /**
     * Indien er een error is bij het loggen
     */
    private function logError()
    {
        $data = 
        [
            'user_id' => '0',
            'log_code' => '1',
            'log_message' => 'Probleem bij wegschrijven van de log',
            'log_created_at' => date('Y-m-d H:i:s')
        ];

        $this->writeLog($data);
    }

    /**
     * Schrijf de log weg naar de db
     * @param $data array
     */
    private function writeLog($data)
    {
        $this->CI->db->insert('logs', $data);
	}
}
