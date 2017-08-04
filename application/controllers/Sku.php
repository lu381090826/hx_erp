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
            $real_path = $file_path . $data['upload_data']['file_name'];
            $this->resize_image($real_path, 120, 120, $data['upload_data']['file_name']);
            return $real_path;
        }
    }


    private function resize_image($uploadfile, $maxwidth, $maxheight, $name)
    {
        //取得当前图片大小
        $width = imagesx($uploadfile);
        $height = imagesy($uploadfile);
        $i = 0.5;
        //生成缩略图的大小
        if (($width > $maxwidth) || ($height > $maxheight)) {
            /*
            $widthratio = $maxwidth/$width;
            $heightratio = $maxheight/$height;

            if($widthratio < $heightratio)
            {
             $ratio = $widthratio;
            }
            else
            {
              $ratio = $heightratio;
            }

            $newwidth = $width * $ratio;
            $newheight = $height * $ratio;
            */
            $newwidth = $width * $i;
            $newheight = $height * $i;
            if (function_exists("imagecopyresampled")) {
                $uploaddir_resize = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($uploaddir_resize, $uploadfile, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            } else {
                $uploaddir_resize = imagecreate($newwidth, $newheight);
                imagecopyresized($uploaddir_resize, $uploadfile, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }

            ImageJpeg($uploaddir_resize, $name);
            ImageDestroy($uploaddir_resize);
        } else {
            ImageJpeg($uploadfile, $name);
        }
    }

    public
    function sku_detail($id)
    {
        $sku_info = $this->m_sku->get_row_by_id($id);

        $this->load->view('/goods/goods/detail', $sku_info['result_rows']);
    }
}
