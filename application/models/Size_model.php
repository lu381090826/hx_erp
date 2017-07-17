<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Size_model extends HX_Model
{

    private $table = "t_size";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function size_delete_by_id($id)
    {
        return $this->db->update($this->table, ['Fstatus' => 0], ['Fid' => $id]);
    }

    public function get_size_list($page = 1)
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

    public function check_size_num_available($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fsize_num = ? ;";

        $ret = $this->db->query($s, [
            $request['size_num']
        ]);
        if (!$ret->row(0)) {
            return $this->suc_out_put();
        }
        return $this->fail_out_put(1000, "尺码代码已存在");
    }

    private function insert_size_check($request)
    {
        $msg = "";
        if (!isset($request['size_info'])) {
            $msg = "尺码信息不能为空";
        }
        if (!isset($request['size_num'])) {
            $msg = "尺码代码不能为空";
        }

        $ret = $this->check_size_num_available($request);
        if ($ret['result'] != 0) {
            $msg = $ret['res_info'];
        }
        if ($msg)
            show_error($msg);
    }

    public function insert_size($request)
    {
        $this->insert_size_check($request);

        $insert_arr = [
            'Fsize_info' => $request['size_info'],
            'Fsize_num' => $request['size_num'],
            'Fmemo' => $request['memo'],
        ];
        $this->db->insert($this->table, $insert_arr);
    }

}