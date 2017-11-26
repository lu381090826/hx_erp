<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 商品管理
 * */

class Goods extends HX_Controller
{

    public function index()
    {
        $this->load->model('goods/category_model', 'category_m');
        $data['category_list'] = $this->category_m->category_cache();

        $this->load->model('goods/color_model', 'color_m');
        $data['color_list'] = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $data['size_list'] = $this->size_m->size_cache();

        $this->load->view('goods/index', $data);
    }

    //商品下架
    public function action_sell_state_off($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $this->goods_m->sell_off($goods_id);
    }

    //商品上架
    public function action_sell_state_on($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $this->goods_m->sell_on($goods_id);
    }

    public function action_export()
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $request = $this->input->post();

        $request['limit'] = 10000;
        $res = $this->goods_m->search_goods($request)['result_rows'];

        $excel = $this->export_init($res);

        $this->export_excel($excel);
    }

    public function get_category()
    {
        $this->load->model('goods/category_model', 'category_m');
        $result = $this->category_m->category_cache_series();

        json_out_put($result);
    }

    public function get_color($page = 0)
    {
        $this->load->model('goods/color_model', 'color_m');
        $result = $this->color_m->get_color_list($page);

        json_out_put($result);
    }

    public function get_size($page = 0)
    {
        $this->load->model('goods/size_model', 'size_m');
        $result = $this->size_m->get_size_list($page);

        json_out_put($result);
    }

    public function get_shop($page = 0)
    {
        $this->load->model('goods/shop_model', 'shop_m');
        $result = $this->shop_m->get_shop_list($page);

        json_out_put($result);
    }

    public function get_sku($page = 0)
    {
        $this->load->model('goods/sku_model', 'sku_m');
        $result = $this->sku_m->get_sku_list($page);

        json_out_put($result);
    }

    public function get_sku_list_info($goods_id)
    {
        $this->load->model('goods/sku_model', 'sku_m');
        $result = $this->sku_m->get_sku_list_info_by_goods_id($goods_id);

        json_out_put($result);
    }

    public function goods_detail($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $data = $this->goods_m->get_row_by_id($goods_id);

        $this->load->model('goods/sku_model', 'sku_m');
        $data['result_rows']['sku_list'] = $this->sku_m->get_sku_list_info_by_goods_id($goods_id)['result_rows'];

        $this->load->model('goods/color_model', 'color_m');
        $data['result_rows']['color_cache'] = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $data['result_rows']['size_cache'] = $this->size_m->size_cache();

        $this->load->model('goods/category_model', 'category_m');
        $data['result_rows']['category_list'] = $this->category_m->category_cache();

        $this->load->view('/goods/goods/detail', $data['result_rows']);
    }

    public function goods_detail_edit($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $data = $this->goods_m->get_row_by_id($goods_id);

        $this->load->model('goods/sku_model', 'sku_m');
        $data['result_rows']['sku_list'] = $this->sku_m->get_sku_list_by_goods_id($goods_id);

        $this->load->model('goods/category_model', 'category_m');
        $data['result_rows']['category_list'] = $this->category_m->category_cache();

        $this->load->view('/goods/goods/detail_edit', $data['result_rows']);
    }

    public function get_goods($page = 0)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $request = $this->input->post();
        $request['page'] = $page;
        $res = $this->goods_m->search_goods($request);
        json_out_put($res);
    }

    public function delete_sku($goods_id)
    {
        $this->load->model('goods/goods_model', 'goods_m');
        $this->goods_m->delete_goods($goods_id);

        $this->load->model('goods/sku_model', 'sku_m');
        $this->sku_m->unset_sku($goods_id);
    }

    public function search_goods()
    {
        $this->load->model('goods/goods_model', 'goods_m');

        $request = $this->input->post();

        $result = $this->goods_m->search_goods($request);

        json_out_put($result);
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
        header('Content-Disposition:attachment;filename="' . date('YmdHi') . '.xlsx"');
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

        $this->load->model('admin/user_model', 'user_m');
        $category_list = $this->user_m->category_cache();

//加载PHPExcel的类
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
//创建PHPExcel实例
        $excel = $this->phpexcel;
        $excel->getActiveSheet()->setCellValue("A1", "货号");
        $excel->getActiveSheet()->setCellValue("B1", "成本");
        $excel->getActiveSheet()->setCellValue("C1", "价格");
        $excel->getActiveSheet()->setCellValue("D1", "品牌");
        $excel->getActiveSheet()->setCellValue("F1", "分类");
        $excel->getActiveSheet()->setCellValue("G1", "备注");
        $excel->getActiveSheet()->setCellValue("H1", "状态");
        $excel->getActiveSheet()->setCellValue("I1", "操作人");
        $excel->getActiveSheet()->setCellValue("J1", "创建时间");
        $excel->getActiveSheet()->setCellValue("K1", "修改时间");
//下面介绍项目中用到的几个关于excel的操作
        foreach ($res as $j => $r) {
            $k = $j + 2;
            //为单元格赋值
            $excel->getActiveSheet()->setCellValue("A{$k}", $r['goods_id']);
            $excel->getActiveSheet()->setCellValue("B{$k}", $r['cost']);
            $excel->getActiveSheet()->setCellValue("C{$k}", $r['price']);
            $excel->getActiveSheet()->setCellValue("D{$k}", $r['brand']);
            $excel->getActiveSheet()->setCellValue("E{$k}", $category_list[$r['category_id']]);
            $excel->getActiveSheet()->setCellValue("F{$k}", $r['category']);
            $excel->getActiveSheet()->setCellValue("G{$k}", $r['memo']);
            $excel->getActiveSheet()->setCellValue("H{$k}", $r['status']);
            $excel->getActiveSheet()->setCellValue("I{$k}", $r['op_uid']);
            $excel->getActiveSheet()->setCellValue("J{$k}", $r['create_time']);
            $excel->getActiveSheet()->setCellValue("K{$k}", $r['modify_time']);
        }
        return $excel;
    }
}
