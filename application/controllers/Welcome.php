<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends HX_Controller {

	public function index()
	{
		$this->load->view('landing');
	}
}
