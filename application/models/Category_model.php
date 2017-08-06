<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Category_model extends HX_Model
{


    private $table = "t_category";
    private $table_prefixes = "F";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function check_insert($request)
    {
        $check_res = [];
        if (isset($request['category_name'])) {
            $check_res['Fcategory_name'] = $request['category_name'];
        } else {
            show_error("请输入类名");
        }
        if (isset($request['pid'])) {
            $check_res['Fpid'] = $request['pid'];
        } else {
            $check_res['Fpid'] = 0;
        }
        if (isset($request['memo'])) {
            $check_res['Fmemo'] = $request['memo'];
        } else {
            $check_res['Fmemo'] = '';
        }
        $check_res['Fop_uid'] = $this->session->uid;
        return $check_res;
    }

    public function insert_category($request)
    {
        $inserty_arr = $this->check_insert($request);
        $this->db->insert($this->table, $inserty_arr);
    }

    public function get_all_list()
    {
        $s = "SELECT Fid,Fcategory_name FROM {$this->table} WHERE Fstatus = 1";

        $ret = $this->db->query($s);

        return $this->suc_out_put($ret->result('array'));
    }

    public function get_list($page = 0)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  LIMIT ? , ?";

        $ret = $this->db->query($s, [
            parent::get_offset($page),
            $this->limit
        ]);

        return $this->suc_out_put($ret->result('array'));
    }


    public function category_delete_by_id($id)
    {
        return $this->db->update($this->table, ['Fstatus' => 0, 'Fop_uid' => $this->session->uid], ['Fid' => $id]);
    }
}