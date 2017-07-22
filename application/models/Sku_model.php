<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Sku_model extends HX_Model
{


    private $table = "t_sku";
    private $table_prefixes = "F";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function sku_delete_by_id($id)
    {
        return $this->db->update($this->table, ['Fstatus' => 0], ['Fid' => $id]);
    }

    public function get_sku_list($page = 1)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  LIMIT ? , ?";
        $this->offset = 0;
        $this->limit = 10;
        if ($page < 1) {
            $page = 1;
        }
        $ret = $this->db->query($s, [
            $this->offset + ($page - 1) * $this->limit,
            $this->limit
        ]);

        return $this->suc_out_put($ret->result('array'));
    }

    public function check_product_number_available($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fproduct_number = ? ;";

        $ret = $this->db->query($s, [
            $request['product_number']
        ]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }

    private function insert_sku_check(&$request)
    {
        $ret = $this->check_product_number_available($request);

        if (isset($ret['result_rows']) && count($ret['result_rows']) > 0) {
            $this->reentry = $ret['result_rows']['id'];

            $request['sku_id'] = $ret['result_rows']['sku_id'];
        }

        $msg = "";
        if (!isset($request['name'])) {
            $msg = "商品名不能为空";
        }
        if (!isset($request['sku_id'])) {
            $request['sku_id'] = $this->get_sku_id();
        }
        if (!isset($request['product_number'])) {
            $msg = "商品号不能为空";
        }
        if (!isset($request['bar_code'])) {
            $msg = "条形码不能为空";
        }
        if (!isset($request['record_number'])) {
            $msg = "备案号不能为空";
        }
        if (!isset($request['brand_id'])) {
            $msg = "品牌不能为空";
        }
        if (!isset($request['category_id'])) {
            $msg = "商品类别不能为空";
        }
        if (!isset($request['property_id'])) {
//            $msg = "商品属性不能为空";
            $request['property_id'] = 0;
        }
        if (!isset($request['price'])) {
            $msg = "价格不能为空";
        }
        if (!isset($request['source_area'])) {
            $msg = "原产地不能为空";
        }
        if (!isset($request['import'])) {
//            $msg = "请选择是否进口";
            $request['import'] = 0;
        }
        if (!isset($request['unit'])) {
            $msg = "单位不能为空";
        }
        if (!isset($request['weight'])) {
            $msg = "重量不能为空";
        }
        if (!isset($request['pic'])) {
//            $msg = "图片不能为空";
            $request['pic'] = 0;
        }
        if (!isset($request['color_id'])) {
//            $msg = "颜色不能为空";
            $request['color_id'] = 0;
        }
        if (!isset($request['size_id'])) {
//            $msg = "尺码不能为空";
            $request['size_id'] = 0;
        }
        if (!isset($request['memo'])) {
            $msg = "描述不能为空";
        }
        if (!isset($request['status'])) {
            $request['status'] = 1;
        }

        foreach ($request as $key => $row) {
            $request[$this->table_prefixes . $key] = $row;
            unset($request[$key]);
        }

        if ($msg)
            show_error($msg);
    }

    private function get_sku_id()
    {
        $sku_id = "G" . date("Ymd") . time();
        return $sku_id;
    }

    public function insert_sku($request)
    {
        $this->insert_sku_check($request);
        if ($this->reentry) {
            $this->db->update($this->table, $request, ['Fid' => $this->reentry]);
        } else {
            $this->db->insert($this->table, $request);
        }

    }

}