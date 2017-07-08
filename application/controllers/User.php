<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', 'm_user');
    }

    public function user_detail($uid)
    {
        $user_info = $this->m_user->get_user_row_by_uid($uid);

        $this->load->library('parser');
        $this->parser->parse('sys/user/userDetail', $user_info['result_rows']);
//        $this->load->view('sys/user/userDetail');
    }

}
