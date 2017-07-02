<?php
class HX_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        if (!$this->session->userdata()||empty($this->session->name)) {
            redirect('login');
        }
    }
}

?>
