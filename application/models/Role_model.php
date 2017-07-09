<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Role_model extends HX_Model
{

    private $table = "t_role";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_role_list($page)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1 LIMIT ? , ?;";
        $this->offset = 0;
        $this->limit = 10;
        $ret = $this->db->query($s, [
            $this->offset + ($page - 1) * $this->limit,
            $this->limit
        ]);

        return $this->suc_out_put($ret->result('array'));
    }

    //获取父角色下的所有子角色和当前角色
    public function get_row_by_pid($pid)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1 AND Fpid = ? OR Fid = ? ;";

        return $this->db->query($s, [$pid, $pid])->result('array');

    }

    public function get_row_by_id($id)
    {
        $s = "SELECT * FROM {$this->table}  WHERE Fstatus = 1 AND Fid = ? LIMIT 1;";
        $ret = $this->db->query($s, [$id]);

        return $this->suc_out_put($ret->row(0, 'array'));
    }

    public function get_row_by_ids($ids)
    {
        $s = "SELECT * FROM {$this->table}  WHERE Fstatus = 1 AND Fid IN (" . implode(',', array_unique($ids)) . ");";
        return $this->db->query($s)->result('array');
    }

    public function get_row_by_name($name)
    {
        $s = "SELECT * FROM {$this->table}  WHERE Fstatus = 1 AND Frole_name = ?;";
        return $this->db->query($s, [$name])->row(0, 'array');
    }

    private function insert_role_check($request)
    {
        $msg = "";
        if (!isset($request['role_id'])) {
            $msg = "父角色不能为空";
        }
        if (!isset($request['role_name'])) {
            $msg = "角色名不能为空";
        } elseif (!empty($this->get_row_by_name($request['role_name']))) {
            $msg = "角色名重复";
        }
        if ($msg) {
            show_error($msg);
        }
    }

    //插入新角色
    public function add_new_role($request)
    {
        $this->insert_role_check($request);

        $insert_arr = array(
            'Fpid' => $request['role_id'],
            'Frole_name' => $request['role_name'],
            'Fmemo' => isset($request['memo']) ? $request['memo'] : '',
            'Fstatus' => 1,
        );
        $this->db->insert($this->table, $insert_arr);

        return $this->db->insert_id();
    }
}