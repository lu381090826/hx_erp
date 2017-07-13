<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Success extends HX_Controller
{

    public function index()
    {
        $this->load->view('success/index');
    }
}
