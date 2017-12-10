<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Authority_model extends HX_Model
{

    private $table = "t_authority";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_by_auth_ids($ids)
    {
        $s = "SELECT * FROM {$this->table}  WHERE Fstatus = 1 AND Fid in(" . implode(',', array_unique($ids)) . ");";
        $ret_p = $this->db->query($s)->result('array');

        //子权限
        $s = "SELECT * FROM {$this->table}  WHERE Fstatus = 1 AND Fpid in(" . implode(',', array_unique($ids)) . ");";
        $ret_c = $this->db->query($s)->result('array');

        $ret = array_merge($ret_p,$ret_c);

        return $this->suc_out_put($ret);
    }

    //查出所有子权限
    public function get_all_by_auth_pids($ids)
    {
        $s = "SELECT * FROM {$this->table}  WHERE Fstatus = 1 AND Fpid in(" . implode(',', array_unique($ids)) . ");";
        $ret = $this->db->query($s)->result('array');

        return $this->suc_out_put($ret);
    }

    //查出所有子权限
    public function all_auth_list()
    {
        $s = "SELECT * FROM {$this->table}  WHERE Fstatus = 1 ;";
        $ret = $this->db->query($s)->result('array');

        return $this->suc_out_put($ret);
    }

}