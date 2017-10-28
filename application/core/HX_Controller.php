<?php

class HX_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function pagination($base_url,$total,$per=10)
    {
        $this->load->library('pagination');
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total;
        $config['per_page'] = $per;
        $config['use_page_numbers'] = TRUE;

        $config['first_link'] = '首页';
        $config['last_link'] = '尾页';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="am-active">';    //“当前页”链接的打开标签。
        $config['cur_tag_close'] = '</li>';
        $config['cur_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}

?>
