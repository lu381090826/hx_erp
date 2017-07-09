<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class User_model extends HX_Model
{

    private $table = "t_user";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function get_user_info_by_password()
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1 AND Fmobile = ? AND Fpassword = ? ;";

        $ret = $this->db->query($s, [
            $this->input->post('mobile'),
            md5($this->input->post('password'))
        ]);

        return $ret->row(0);
    }

    public function check_mobile_available($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fmobile = ? ;";

        $ret = $this->db->query($s, [
            $request['mobile']
        ]);
        if (!$ret->row(0)) {
            return $this->suc_out_put();
        }
        return $this->fail_out_put(1000, "手机号已存在");
    }

    public function get_user_list($page)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT r.Frole_name,u.* FROM t_user u LEFT JOIN t_role r ON r.Fid = u.Frole_id WHERE u.Fstatus = 1  LIMIT ? , ?";
        $this->offset = 0;
        $this->limit = 10;
        $ret = $this->db->query($s, [
            $this->offset + ($page - 1) * $this->limit,
            $this->limit
        ]);

        return $this->suc_out_put($ret->result('array'));
    }

    public function get_row_by_uid($uid)
    {
        $s = "SELECT r.Frole_name,u.* FROM t_user u LEFT JOIN t_role r ON r.Fid = u.Frole_id WHERE u.Fstatus = 1 AND Fuid = ? LIMIT 1;";
        $ret = $this->db->query($s, [$uid]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }

    private function insert_user_check($request)
    {
        $msg = "";
        if (!isset($request['name'])) {
            $msg = "用户名不能为空";
        }
        if (!isset($request['mobile'])) {
            $msg = "手机号不能为空";
        }
        if (!isset($request['password'])) {
            $msg = "密码不能为空";
        }
        if (!isset($request['email'])) {
            $msg = "邮箱不能为空";
        }
        if (!isset($request['role_id'])) {
            $msg = "角色不能为空";
        }
        $ret = $this->check_mobile_available($request);
        if ($ret['result'] != 0) {
            $msg = $ret['res_info'];
        }
        if ($msg)
            show_error($msg);
    }

    public function insert_user($request)
    {
        $this->insert_user_check($request);

        $insert_arr = [
            'Fname' => $request['name'],
            'Fmobile' => $request['mobile'],
            'Fpassword' => md5($request['password']),
            'Femail' => $request['email'],
            'Frole_id' => $request['role_id'],
            'Fmemo' => '',
        ];
        $this->db->insert($this->table, $insert_arr);
    }
}