<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sku extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sku_model', 'm_sku');
    }

    public function action_add_sku()
    {
        $this->load->view('goods/sku/addForm');
    }

    public function add_sku()
    {
        $post = $this->input->post();
        $post['pic'] = $this->upload_file();
        $this->m_sku->insert_sku($post);


        $this->load->helper('url');
        redirect("success");
    }

    public function delete_sku($id)
    {
        $this->m_sku->sku_delete_by_id($id);

        $this->load->helper('url');
        redirect("success");
    }

    /**
     * @param $config
     */
    private function upload_file()
    {
        $config['upload_path'] = '/uploads/' . date("Ymd") . '/sku_pic/' . '/';
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }
        $config['allowed_types'] = 'gif|jpg|png';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('pic')) {
            $error = array('error' => $this->upload->display_errors());

            show_error($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            return $data['upload_data']['full_path'];
        }
    }
}
