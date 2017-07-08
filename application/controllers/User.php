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
        $user_info = $this->m_user->get_row_by_uid($uid);

        $this->load->library('parser');
        $this->parser->parse('sys/user/detail', $user_info['result_rows']);
    }

}
