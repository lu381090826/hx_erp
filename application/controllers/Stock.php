<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends HX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('goods/stock_model', 'stock_m');
        $this->load->model('goods/sku_model', 'sku_m');
        $this->load->model('goods/color_model', 'color_m');
        $this->load->model('goods/size_model', 'size_m');
    }

    public function get_by_sku_id($sku_id, $page = 1)
    {

        $data['sku_info'] = $this->sku_m->get_by_sku_id($sku_id)['result_rows'];
        $data['stock_list'] = $this->stock_m->get_list_by_sku_id($sku_id, $page);


        $data['color_list'] = $this->color_m->get_color_list_all()['result_rows'];
        $data['size_list'] = $this->size_m->get_size_list_all()['result_rows'];
        $data['pagination'] = parent::pagination('/stock/get_by_sku_id/' . $sku_id, $data['stock_list']['total_num'], 10);

        foreach ($data['stock_list']['result_rows'] as &$row) {
            $row['color_name'] = '';
            $row['color_code'] = '';
            $row['color_num'] = '';
            foreach ($data['color_list'] as $color) {
                if ($color['id'] == $row['color_id']) {
                    $row['color_name'] = $color['name'];
                    $row['color_code'] = $color['color_code'];
                    $row['color_num'] = $color['color_num'];
                }
            }
            $row['size_info'] = '';
            $row['size_num'] = '';
            foreach ($data['size_list'] as $size) {
                if ($size['id'] == $row['size_id']) {
                    $row['size_info'] = $size['size_info'];
                    $row['size_num'] = $size['size_num'];
                }
            }
        }
        $this->load->view('goods/stock/index', $data);
    }

    public function modify_stock()
    {
        $post = $this->input->post();
        if (isset($post['stock'])) {
            if (count($post['stock']) > 5) {
                show_error("提交条数过多，请一次不要超过5条");
            }
            foreach ($post['stock'] as $stock) {
                $this->stock_m->modify_stock($stock);
            }
        }

        if (isset($post['update_stock'])) {
            if (count($post['update_stock']) > 10) {
                show_error("修改条数过多，请一次不要超过10条");
            }
            foreach ($post['update_stock'] as $stock) {
                $this->stock_m->update_num($stock['stock_id'], $stock['num']);
            }
        }

        $this->load->view('success/index');

    }
}
