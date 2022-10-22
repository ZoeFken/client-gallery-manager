<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// include_once APPPATH.'/third_party/mpdf/mpdf.php';
include_once APPPATH.'/third_party/vendor/autoload.php';

class M_pdf 
{
    public $param;
    public $pdf;
    public function __construct($param = "'c', 'A4-L'")
    {
        $this->param =$param;
        $this->pdf = new mPDF($this->param);
    }
}