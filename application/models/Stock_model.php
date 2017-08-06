<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Stock_model extends HX_Model
{


    private $table = "t_stock";
    private $table_prefixes = "F";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_list_by_sku_id($sku_id, $page)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fsku_id = ? ORDER BY Fcreate_time DESC LIMIT ? , ?";
        $ret = $this->db->query($s, [
            $sku_id,
            parent::get_offset($page),
            $this->limit
        ]);

        return $this->suc_out_put($ret->result('array'));
    }

    public function get_stock_by_color_and_size($sku_id, $color_id, $size_id)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fsku_id = ? AND Fcolor_id=? AND Fsize_id = ?
        ORDER BY Fcreate_time DESC LIMIT 1";
        $ret = $this->db->query($s, [$sku_id, $color_id, $size_id]);

        return $this->suc_out_put($ret->result('array'));
    }

    public function insert($request)
    {
        $insert_arr = [
            'Fstock_id' => $request['sku_id'] . 'C' . $request['color_id'] . 'S' . $request['size_id'],
            'Fsku_id' => $request['sku_id'],
            'Fcolor_id' => $request['color_id'],
            'Fsize_id' => $request['size_id'],
            'Fstock_num' => $request['num'],
            'Fop_uid' => $this->session->uid,
            'Fmemo' => '',
        ];
        $this->db->insert($this->table, $insert_arr);
    }

    public function update_num($stock_id, $num)
    {
        $this->db->update($this->table, ['Fstock_num' => $num, 'Fop_uid' => $this->session->uid], ['Fstock_id' => $stock_id]);
    }

    public function modify_stock($request)
    {
        $check_available = $this->get_stock_by_color_and_size($request['sku_id'], $request['color_id'], $request['size_id']);
        if (empty($check_available['result_rows'][0])) {
            $this->insert($request);
        } else {
            $this->update_num($check_available['result_rows'][0]['stock_id'], $request['num'] + $check_available['result_rows'][0]['stock_num']);
        }
    }
}