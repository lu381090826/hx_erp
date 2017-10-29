<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Shop_model extends HX_Model
{

    private $table = "t_shop";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function shop_delete_by_id($id)
    {
        return $this->db->update($this->table, ['Fstatus' => 0], ['Fid' => $id]);
    }

    public function get_shop_list_all()
    {
        $s = "SELECT Fid,Fshop_info,Fshop_num FROM {$this->table} WHERE Fstatus = 1";

        $ret = $this->db->query($s);

        return $this->suc_out_put($ret->result('array'));
    }

    public function get_shop_list($page = 1)
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

    public function check_shop_name_available($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fname = ? ;";

        $ret = $this->db->query($s, [
            $request['name']
        ]);
        if (!$ret->row(0)) {
            return $this->suc_out_put();
        }
        return $this->fail_out_put(1000, "店铺已存在");
    }

    private function check_input($request)
    {
        $insert_params = [];
        if (!empty($request['name'])) {
            $insert_params['Fname'] = $request['name'];
        } else {
            show_error("店铺名称不能为空");
        }

        $ret = $this->check_shop_name_available($request);
        if ($ret['result'] != 0) {
            show_error($ret['res_info']);
        }

        if (!empty($request['memo'])) {
            $insert_params['Fmemo'] = $request['memo'];
        }
        return $insert_params;
    }

    public function insert_shop($request)
    {
        $insert_arr = $this->check_input($request);

        $this->db->insert($this->table, $insert_arr);
    }

}