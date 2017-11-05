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
        $s = "SELECT Fid,Fname FROM {$this->table} WHERE Fstatus = 1";

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
        return $this->fail_out_put(1000, "该店铺名已存在");
    }

    private function check_input($request)
    {
        $insert_params = [];
        if (!empty($request['name'])) {
            $insert_params['Fname'] = $request['name'];
        } else {
            show_error("请填写店铺姓名");
        }
        $ret = $this->check_shop_name_available($request);
        if ($ret['result'] != 0) {
            show_error($ret['res_info']);
        }
        if (!empty($request['owner'])) {
            $insert_params['Fowner'] = $request['owner'];
        } else {
            show_error("请填写负责人");
        }
        if (!empty($request['owner_mobile'])) {
            $insert_params['Fowner_mobile'] = $request['owner_mobile'];
        } else {
            show_error("请填写负责人电话");
        }

        if (!empty($request['phone'])) {
            $insert_params['Fphone'] = $request['phone'];
        }
        if (!empty($request['address'])) {
            $insert_params['Faddress'] = $request['address'];
        }
        if (!empty($request['email'])) {
            $insert_params['Femail'] = $request['email'];
        }
        if (!empty($request['web_home'])) {
            $insert_params['Fweb_home'] = $request['web_home'];
        }
        $insert_params['Foperator'] = $this->session->uid;

        if (!empty($request['memo'])) {
            $insert_params['Fmemo'] = $request['memo'];
        }else{
            $insert_params['Fmemo'] = '';
        }
        return $insert_params;
    }

    public function insert_shop($request)
    {
        $insert_arr = $this->check_input($request);

        $this->db->insert($this->table, $insert_arr);

        if (isset($request['seller_id'])) {
            $this->seller_addto_shop($request['seller_id'],
                $this->db->insert_id());
        }
    }

    private function check_update_input($request)
    {
        unset($request['seller_id']);
        $params = [];
        foreach ($request as $k => $row) {
            $params['F' . $k] = $row;
        }

        return $params;
    }

    public function update_shop($request)
    {
        $arr = $this->check_update_input($request);

        $this->db->update($this->table, $arr, ['Fid' => $request['id']]);

        if (isset($request['seller_id'])) {
            $this->seller_addto_shop($request['seller_id'],$request['id']);
        }
    }

    public function shop_detail_by_id($id)
    {
        $s = "SELECT * FROM {$this->table} u  WHERE u.Fstatus = 1 AND Fid = ? LIMIT 1;";
        $ret = $this->db->query($s, [$id]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }

    private function seller_addto_shop($seller_id_arr, $shop_id)
    {
        //先清空再新增
        $this->db->update("t_shop_seller", ['Fstatus' => 0], ['Fshop_id' => $shop_id]);
        $arr = [];
        foreach ($seller_id_arr as $k => $r) {
            $arr['Fseller_id'] = $r;
            $arr['Fstatus'] = 1;
            $arr['Fshop_id'] = $shop_id;
            $arr['Foperator'] = $this->session->uid;
            $this->db->replace("t_shop_seller", $arr);
        }
    }
}