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
            } else {
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

    public function action_delete_sku($id)
    {
        $this->sku_m->sku_delete_by_id($id);
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

    private function imageCreateFromAny($filepath)
    {
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

    public function action_export()
    {
        $this->load->model('goods/sku_model', 'sku_m');
        $request = $this->input->post();

        $request['limit'] = 10000;
        $res = $this->sku_m->search_sku(1, $request)['result_rows'];

        $excel = $this->export_init($res);

        $this->export_excel($excel);
    }

    /**
     * @param $excel
     */
    private function export_excel($excel)
    {
        //输出到浏览器
        $write = new PHPExcel_Writer_Excel2007($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . date('YmdHi') . '导出-' . $this->session->name . '.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }

    /**
     * @param $res
     * @param $category_list
     * @return mixed
     */
    private function export_init($res)
    {
        $this->load->model('goods/category_model', 'category_m');
        $category_list = $this->category_m->category_cache();

        $this->load->model('goods/color_model', 'color_m');
        $color_list = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $size_list = $this->size_m->size_cache();

        $this->load->model('admin/user_model', 'user_m');
        $user_list = $this->user_m->user_cache();

        $status = [0 => "已删除", 1 => "上架中", 2 => "已下架"];

//加载PHPExcel的类
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
//创建PHPExcel实例
        $excel = $this->phpexcel;
        $excel->getActiveSheet()->setCellValue("A1", "SKU_ID");
        $excel->getActiveSheet()->setCellValue("B1", "货号");
        $excel->getActiveSheet()->setCellValue("C1", "成本");
        $excel->getActiveSheet()->setCellValue("D1", "价格");
        $excel->getActiveSheet()->setCellValue("E1", "品牌");
        $excel->getActiveSheet()->setCellValue("F1", "分类");
        $excel->getActiveSheet()->setCellValue("G1", "颜色");
        $excel->getActiveSheet()->setCellValue("H1", "颜色代码");
        $excel->getActiveSheet()->setCellValue("I1", "尺寸");
        $excel->getActiveSheet()->setCellValue("J1", "尺寸代码");
        $excel->getActiveSheet()->setCellValue("K1", "备注");
        $excel->getActiveSheet()->setCellValue("L1", "状态");
        $excel->getActiveSheet()->setCellValue("M1", "操作人");
        $excel->getActiveSheet()->setCellValue("N1", "创建时间");
        $excel->getActiveSheet()->setCellValue("O1", "修改时间");

//下面介绍项目中用到的几个关于excel的操作
        foreach ($res as $j => $r) {
            $k = $j + 2;
            //为单元格赋值
            $excel->getActiveSheet()->setCellValue("A{$k}", $r['sku_id']);
            $excel->getActiveSheet()->setCellValue("B{$k}", $r['goods_id']);
            $excel->getActiveSheet()->setCellValue("C{$k}", $r['cost']);
            $excel->getActiveSheet()->setCellValue("D{$k}", $r['price']);
            $excel->getActiveSheet()->setCellValue("E{$k}", $r['brand']);
            $excel->getActiveSheet()->setCellValue("F{$k}", isset($category_list[$r['category_id']]) ? $category_list[$r['category_id']] : "");
            $excel->getActiveSheet()->setCellValue("G{$k}", isset($color_list[$r['color_id']]['name']) ? $color_list[$r['color_id']]['name'] : "");
            $excel->getActiveSheet()->setCellValue("H{$k}", $r['color_id']);
            $excel->getActiveSheet()->setCellValue("I{$k}", isset($size_list[$r['size_id']]['size_info']) ? $size_list[$r['size_id']]['size_info'] : "");
            $excel->getActiveSheet()->setCellValue("J{$k}", $r['size_id']);
            $excel->getActiveSheet()->setCellValue("K{$k}", $r['memo']);
            $excel->getActiveSheet()->setCellValue("L{$k}", $status[$r['status']]);
            $excel->getActiveSheet()->setCellValue("M{$k}", isset($user_list[$r['op_uid']]) ? $user_list[$r['op_uid']]['name'] : "");
            $excel->getActiveSheet()->setCellValue("N{$k}", $r['create_time']);
            $excel->getActiveSheet()->setCellValue("O{$k}", $r['modify_time']);
        }
        return $excel;
    }

}
