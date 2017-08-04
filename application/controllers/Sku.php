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
        $post['pic'] = $this->upload_file()['small_path'];
        $post['pic_normal'] = $this->upload_file()['normal_path'];
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
        $file_path = '/sku_pic_uploads/' . date("Ymd") . '/';
        if (!file_exists(FCPATH . 'sku_pic_uploads/')) {
            mkdir(FCPATH . 'sku_pic_uploads/');
        }
        $config['upload_path'] = FCPATH . 'sku_pic_uploads/' . date("Ymd") . '/';
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }
        $config['allowed_types'] = 'gif|jpg|png|jpeg';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('pic')) {
            $error = array('error' => $this->upload->display_errors());

            show_error($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            $real_path = FCPATH . $file_path . $data['upload_data']['file_name'];
            $new_real_path = $this->resize_image($real_path, 80, 100, $data['upload_data']['file_name'], $config['upload_path']);
            return ['small_path' => $new_real_path, 'normal_path' => $file_path . $data['upload_data']['file_name']];
        }
    }

    /*
     * 切割图片
     * */
    private function resize_image($uploadfile, $maxwidth, $maxheight, $name, $path)
    {
        $uploadedfile = $uploadfile;
        $src = imagecreatefromjpeg($uploadedfile);

        list($width, $height) = getimagesize($uploadedfile);

        $newwidth = $maxwidth;
        $newheight = ($height / $width) * $maxheight;
        $tmp = imagecreatetruecolor($newwidth, $newheight);

        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $newPath = $path . "small_images/";
        if (!file_exists($newPath)) {
            mkdir($newPath);
        }
        $filename = $newPath . $name;
        imagejpeg($tmp, $filename, 100);
        imagedestroy($src);
        imagedestroy($tmp);

        return '/sku_pic_uploads/' . date("Ymd") . '/small_images/' . $name;
    }

    public function sku_detail($id)
    {
        $sku_info = $this->m_sku->get_row_by_id($id);

        $this->load->view('/goods/goods/detail', $sku_info['result_rows']);
    }
}
