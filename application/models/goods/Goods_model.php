<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Goods_model extends HX_Model
{


    private $table = "t_goods";
    private $table_prefixes = "F";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_row_by_id($goods_id)
    {
        $s = "SELECT * FROM {$this->table}   WHERE Fgoods_id = ? LIMIT 1;";
        $ret = $this->db->query($s, [$goods_id]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }

    private function modify_goods_check($request)
    {
        $insert_params = [];
        $insert_params['Fname'] = isset($request['name']) ? $request['name'] : '';

        if (empty($request['goods_id'])) {
            show_error('商品编号有误');
        } else {
            $check_reentry = $this->get_row_by_id($request['goods_id']);
            if (!empty($check_reentry['result_rows'])) {
                //重入id
                $this->reentry = $request['goods_id'];
            }
            $insert_params['Fgoods_id'] = $request['goods_id'];
        }

        if (empty($request['price'])) {
            show_error('价格有误');
        } else {
            $insert_params['Fprice'] = $request['price'];
        }

        if (empty($request['cost'])) {
            show_error('成本有误');
        } else {
            $insert_params['Fcost'] = $request['cost'];
        }

        if (!empty($request['record_number'])) {
            $insert_params['Frecord_number'] = $request['record_number'];
        }
        if (!empty($request['brand'])) {
            $insert_params['Fbrand'] = $request['brand'];
        }
        if (!empty($request['pic'])) {
            $insert_params['Fpic'] = $request['pic'];
        }
        if (!empty($request['pic_normal'])) {
            $insert_params['Fpic_normal'] = $request['pic_normal'];
        }
        if (!empty($request['category_id'])) {
            $insert_params['Fcategory_id'] = $request['category_id'];
        }
        if (!empty($request['memo'])) {
            $insert_params['Fmemo'] = $request['memo'];
        }
        if (!empty($request['status'])) {
            $insert_params['Fstatus'] = $request['status'];
        } else {
            $insert_params['Fstatus'] = 1;
        }
        if (!empty($request['op_uid'])) {
            $insert_params['Fop_uid'] = $this->session->uid;
        }
        return $insert_params;
    }

    public function modify_goods($request)
    {
        $insert_params = $this->modify_goods_check($request);
        if ($this->reentry) {
            $this->db->update($this->table, $insert_params, ['Fgoods_id' => $this->reentry]);
        } else {
            $this->db->insert($this->table, $insert_params);
        }

        if (!empty($request['shop_id'])) {
            $this->goods_addto_shop($request['shop_id'], $request['goods_id']);
        }

    }

    public function goods_addto_shop($shop_id_arr, $goods_id)
    {
        //先清空再新增
        $this->db->update("t_shop_goods", ['Fstatus' => 0], ['Fgoods_id' => $goods_id]);
        $arr = [];
        foreach ($shop_id_arr as $k => $r) {
            $arr['Fshop_id'] = $r;
            $arr['Fstatus'] = 1;
            $arr['Fgoods_id'] = $goods_id;
            $arr['Foperator'] = $this->session->uid;
            $this->db->replace("t_shop_goods", $arr);
        }
    }

    public function get_goods_list($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  ORDER BY Fcreate_time DESC LIMIT ? , ?";

        list($offset, $limit) = parent::pageUtils($request);

        $ret = $this->db->query($s, [
            $offset,
            $limit
        ]);

        return $this->suc_out_put($ret->result('array'));
    }

    //获取商品所在店铺
    public function get_goods_shop($goods_id)
    {
        $s = "SELECT Fshop_id FROM t_shop_goods WHERE Fstatus = 1 AND Fgoods_id= ?  ORDER BY Fcreate_time DESC";

        $ret = $this->db->query($s, [$goods_id]);

        return $ret->result('array');
    }

    private function searchParams($request)
    {
        $sql = "";
        if (!empty($request['goods_id'])) {
            $sql .= " AND Fgoods_id LIKE '%{$request['goods_id']}%' ";
        }
        if (!empty($request['price_max'])) {
            $sql .= " AND Fprice <= {$request['price_max']} ";
        }
        if (!empty($request['price_min'])) {
            $sql .= " AND Fprice >= {$request['price_min']} ";
        }
        if (!empty($request['record_number'])) {
            $sql .= " AND Frecord_number LIKE '%{$request['record_number']}%' ";
        }
        if (!empty($request['brand'])) {
            $sql .= " AND Fbrand LIKE '%{$request['brand']}%' ";
        }
        if (!empty($request['category_id'])) {
            $sql .= " AND Fcategory_id = {$request['category_id']} ";
        }
        if (!empty($request['category'])) {
            $sql .= " AND Fcategory LIKE '%{$request['category']}%' ";
        }
        if (!empty($request['begin_time'])) {
            $sql .= " AND Fcreate_time >= '{$request['begin_time']}' ";
        }
        if (!empty($request['end_time'])) {
            $sql .= " AND Fcreate_time <= '{$request['end_time']}' ";
        }

        //销售只能看自己店铺的内容
        $this->config->load('user_type', 'user_type');
        if ($this->session->role_id == $this->config->item('user_type')['seller']) {
            $goods_ids = $this->get_seller_goods();
            if (!empty($goods_ids)) {
                $goods_ids_str = implode('","', $this->get_seller_goods());
                $sql .= " AND Fgoods_id in (\"{$goods_ids_str}\") ";
            } else {
                $sql .= " AND Fgoods_id = '0' ";
            }
        }
        if (isset($request['status']) && $request['status'] != '') {
            $status = "fstatus = {$request['status']}";
        } else {
            $status = "fstatus > 0";
        }
        $sql = "WHERE {$status} " . $sql;
        return $sql;
    }

    private function get_seller_goods()
    {
        $s = "SELECT Fshop_id FROM t_shop_seller WHERE Fstatus = 1 AND Fseller_id = ? ORDER BY Fcreate_time DESC";
        $ret = $this->db->query($s, [$this->session->uid]);
        $shop_query = $ret->result('array');
        $shop_ids = [];
        foreach ($shop_query as $row) {
            $shop_ids[] = $row['shop_id'];
        }

        if (count($shop_ids)) {
            $shop_ids_str = implode("','", $shop_ids);
            $s = "SELECT Fgoods_id FROM t_shop_goods WHERE Fstatus = 1 AND Fshop_id in ('{$shop_ids_str}')";
            $ret = $this->db->query($s);
            $goods_query = $ret->result('array');

            $goods_ids = [];
            foreach ($goods_query as $row) {
                $goods_ids[] = $row['goods_id'];
            }
            return $goods_ids;
        } else {
            return null;
        }
    }

    public function sell_off($goods_id)
    {
        $status = config_load('goods_status', 'goods_sell_off');

        $this->db->set('Fstatus', $status);
        $this->db->where('Fgoods_id', $goods_id);
        $this->db->update($this->table);

        $this->db->set('Fstatus', $status);
        $this->db->where('Fgoods_id', $goods_id);
        $this->db->update('t_sku');
    }

    public function sell_on($goods_id)
    {
        $status = config_load('goods_status', 'goods_sell_on');

        $this->db->set('Fstatus', $status);
        $this->db->where('Fgoods_id', $goods_id);
        $this->db->update($this->table);

        $this->db->set('Fstatus', $status);
        $this->db->where('Fgoods_id', $goods_id);
        $this->db->update('t_sku');
    }

    public function search_goods($request)
    {
        $params = $this->searchParams($request);

        $s = "SELECT count(1) as Fcount FROM {$this->table} {$params}";
        $ret = $this->db->query($s);
        log_in($this->db->last_query());
        $this->total_num = $ret->row(0, 'array')['count'];

        $s = "SELECT Fgoods_id FROM {$this->table} {$params} ORDER BY Fmodify_time DESC LIMIT ? , ?";

        list($offset, $limit) = parent::pageUtils($request);

        $ret = $this->db->query($s, [
            $offset,
            $limit
        ]);
        $result_arr = $ret->result('array');

        if (!empty($result_arr)) {
            $s = "SELECT Fgoods_id,Fcost,Fprice,Fpic,Fpic_normal,Fbrand,Fcategory_id,Fcategory,Fmemo,Fstatus,Fop_uid,Fstatus,Fcreate_time,Fmodify_time FROM {$this->table} WHERE Fgoods_id in ('" . implode("','", array_column($result_arr, "goods_id")) . "')";

            $ret = $this->db->query($s);
            log_in($this->db->last_query());
            $result_arr = $ret->result('array');
        }

        return $this->suc_out_put($result_arr);
    }

    public function delete_goods($goods_id)
    {
        $this->db->update($this->table, ["Fstatus" => 0], ["Fgoods_id" => $goods_id]);
    }

}