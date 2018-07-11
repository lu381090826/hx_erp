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

        return $ret->row(0, 'array');
    }

    public function get_user_info_by_dingtalk_userid($userid)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1 AND Fdk_userid = ? ;";

        $ret = $this->db->query($s, [
            $userid
        ]);

        return $ret->row(0, 'array');
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
        if (isset($request['uid']) && $ret->row(0)->uid == $request['uid']) {
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
            'Fshop_id' => $request['shop_id'],
            'Fdk_userid' => isset($request['userid']) ? $request['userid'] : '',
            'Fmemo' => '',
        ];
        $this->db->insert($this->table, $insert_arr);
    }

    public function update_user($request)
    {

        $ret = $this->check_mobile_available($request);
        if ($ret['result'] != 0) {
            show_error($ret['res_info']);
        }

        if (!empty($request['name'])) {
            $insert_arr['Fname'] = $request['name'];
        }
        if (!empty($request['mobile'])) {
            $insert_arr['Fmobile'] = $request['mobile'];
        }
        if (!empty($request['password']) && $request['password'] != "") {
            $insert_arr['Fpassword'] = md5($request['password']);
        }
        if (!empty($request['email'])) {
            $insert_arr['Femail'] = $request['email'];
        }
        if (!empty($request['role_id'])) {
            $insert_arr['Frole_id'] = $request['role_id'];
        }
        if (!empty($request['shop_id'])) {
            $insert_arr['Fshop_id'] = $request['shop_id'];
        }
        if (!empty($request['memo'])) {
            $insert_arr['Fmemo'] = $request['memo'];
        }
        if (!empty($request['status'])) {
            $insert_arr['Fstatus'] = $request['status'];
        }
        $insert_arr['Fmodify_time'] = date("Y-m-d H:i:s");
        $this->db->update($this->table, $insert_arr, ["Fuid" => $request['uid']]);
    }

    public function get_seller($shopId = null, $forInsert = false)
    {
        $this->config->load('user_type');

        $noInSellerId = '';
        if ($forInsert) {
            //查出已配置店铺的用户进行排除
            $s = "SELECT Fseller_id FROM t_shop_seller WHERE Fstatus = 1";

            $ret = $this->db->query($s);
            $haveShopSeller = [];
            $s = $ret->result('array');
            foreach ($s as $row) {
                array_push($haveShopSeller, $row['seller_id']);
            }
            $haveShopSeller = array_unique($haveShopSeller);
            if (!empty($haveShopSeller)) {
                $noInSellerId = 'and Fuid not in (' . implode($haveShopSeller, ',') . ')';
            }
        }
        $sellerIdString = '';
        $sellerId = [];
        if (!empty($shopId)) {
            $s = $this->get_seller_shop($shopId);
            foreach ($s as $row) {
                array_push($sellerId, $row['seller_id']);
            }
            $sellerId = array_unique($sellerId);
            if (!empty($sellerId)) {
                $sellerIdString = 'and Fuid in (' . implode($sellerId, ',') . ')';
            }
        }

        $seller_role = $this->config->item('user_type')['seller'];
        $s = "SELECT * FROM t_user u WHERE Frole_id = ? {$sellerIdString} {$noInSellerId} ORDER BY Fcreate_time DESC ;";
        $ret = $this->db->query($s, [$seller_role]);

        return $this->suc_out_put($ret->result('array'));
    }

    //获取所在店铺的销售
    public function get_seller_shop($shop_id)
    {
        $s = "SELECT Fseller_id FROM t_shop_seller WHERE Fstatus = 1 AND Fshop_id= ?  ORDER BY Fcreate_time DESC";

        $ret = $this->db->query($s, [$shop_id]);

        return $ret->result('array');
    }

    public function get_user_info($uid)
    {
        $user_info = $this->get_row_by_uid($uid);
        if (empty($user_info['result_rows'])) {
            show_error("用户不存在");
        }
        $this->config->load('user_type', 'user_type');
        if ($user_info['result_rows']['role_id'] == $this->config->item('user_type')['seller']) {
            $this->load->model('goods/shop_model', 'shop_m');
            $shop_info = $this->shop_m->get_user_shop($uid);
            $user_info['result_rows']['shop_info'] = $shop_info;
        }
        return $user_info['result_rows'];
    }

    public function user_cache()
    {
        $arr = [];
        $res = [];

        if ($this->config->item('redis_default')['cache_on']) {
            $user_cache = 'USER_CACHE';
            try {
                $this->load->driver('cache');
                if (empty($this->cache->redis->get($user_cache))) { //如果未设置
                    $this->limit = 100000;
                    $arr = $this->get_user_list(1)['result_rows'];

                    foreach ($arr as $r) {
                        $res[$r['uid']] = $r;
                    }

                    $this->cache->redis->save($user_cache, $arr, 86400 * 365); //设置
                } else {
                    $arr = $this->cache->redis->get($user_cache);  //从缓存中直接读取对应的值
                }

            } catch (Exception $e) {
                log_error($e->getMessage());
            }
        }

        if (empty($arr)) {
            $this->limit = 100000;
            $arr = $this->get_user_list(1)['result_rows'];
            foreach ($arr as $r) {
                $res[$r['uid']] = $r;
            }
        }
        return $res;
    }
}