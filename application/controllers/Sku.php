<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sku extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('goods/sku_model', 'sku_m');
    }

    public function action_add_sku()
    {
        $this->load->model('goods/shop_model', 'shop_m');
        $data['shop_list'] = $this->shop_m->get_shop_list_all()['result_rows'];

        $this->load->model('goods/category_model', 'm_category');
        $data['category_list'] = $this->m_category->category_cache();


        $this->load->model('goods/color_model', 'color_m');
        $data['color_list'] = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $data['size_list'] = $this->size_m->size_cache();

        $this->load->view('goods/sku/addForm', $data);
    }

    public function action_edit_sku($goods_id)
    {
        $this->load->model('goods/shop_model', 'shop_m');
        $data['shop_list'] = $this->shop_m->get_shop_list_all()['result_rows'];

        $this->load->model('goods/category_model', 'm_category');
        $data['category_list'] = $this->m_category->category_cache();

        $this->load->model('goods/color_model', 'color_m');
        $data['color_list'] = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $data['size_list'] = $this->size_m->size_cache();

        $this->load->model('goods/goods_model', 'goods_m');
        $data['goods_info'] = $this->goods_m->get_row_by_id($goods_id)['result_rows'];
        $shops = $this->goods_m->get_goods_shop($goods_id);

        $this->load->model('goods/sku_model', 'sku_m');
        $data['sku_list'] = $this->sku_m->get_sku_list_info_by_goods_id($goods_id)['result_rows'];


        foreach ($data['sku_list'] as $row) {
            $data['color_list'][$row['color_id']]['is_select'] = 1;
            $data['size_list'][$row['size_id']]['is_select'] = 1;
        }

        foreach ($data['shop_list'] as &$row) {
            if (array_keys($shops, ['shop_id' => $row['id']])) {
                $row['is_check'] = true;
            }else{
                $row['is_check'] = false;
            }
        }
        $this->load->view('goods/sku/editForm', $data);
    }

    public function add_sku()
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $post = $this->input->post();

        if (!empty($_FILES['pic']['size'])) {
            $post['pic'] = $this->upload_file()['small_path'];
            $post['pic_normal'] = $this->upload_file()['normal_path'];
        }

        $this->goods_m->modify_goods($post);
        $this->sku_m->modify_sku($post);


        $this->load->helper('url');
        redirect("success");
    }

    public function delete_sku($id)
    {
        $this->sku_m->sku_delete_by_id($id);

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

    private function imageCreateFromAny($filepath) {
        $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = array(
            1,  // [] gif
            2,  // [] jpg
            3,  // [] png
            6   // [] bmp
        );
        if (!in_array($type, $allowedTypes)) {
            return false;
        }
        $im = null;
        switch ($type) {
            case 1 :
                $im = imageCreateFromGif($filepath);
                break;
            case 2 :
                $im = imageCreateFromJpeg($filepath);
                break;
            case 3 :
                $im = imageCreateFromPng($filepath);
                break;
//            case 6 :
//                $im = imageCreateFromBmp($filepath);
//                break;
        }
        return $im;
    }

    /*
     * 切割图片
     * */
    private function resize_image($uploadfile, $maxwidth, $maxheight, $name, $path)
    {
        $uploadedfile = $uploadfile;
        $src = $this->imageCreateFromAny($uploadedfile);

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
        $sku_info = $this->sku_m->get_row_by_id($id);

        $this->load->view('/goods/goods/detail', $sku_info['result_rows']);
    }

    public function sku_detail_edit($id)
    {
        $sku_info = $this->sku_m->get_row_by_id($id);

        $this->load->view('/goods/goods/detail_edit', $sku_info['result_rows']);
    }
}
