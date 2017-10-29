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
        $s = "SELECT * FROM {$this->table} u  WHERE u.Fstatus = 1 AND Fgoods_id = ? LIMIT 1;";
        $ret = $this->db->query($s, [$goods_id]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }

    private function modify_goods_check($request)
    {
        $insert_params = [];
        $insert_params['Fname'] = isset($request['name'])?$request['name']:'';

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

    private function searchParams($request){
        $sql = "";
        if (!empty($request['goods_id'])) {
            $sql .= " AND Fgoods_id LIKE '%{$request['goods_id']}%' ";
        }
        if(!empty($request['price_max'])){
            $sql .= " AND Fprice <= {$request['price_max']} ";
        }
        if(!empty($request['price_min'])){
            $sql .= " AND Fprice >= {$request['price_min']} ";
        }
        if(!empty($request['record_number'])){
            $sql .= " AND Frecord_number LIKE '%{$request['record_number']}%' ";
        }
        if(!empty($request['brand'])){
            $sql .= " AND Fbrand LIKE '%{$request['brand']}%' ";
        }
        if(!empty($request['category_id'])){
            $sql .= " AND Fcategory_id = {$request['category_id']} ";
        }
        if(!empty($request['category'])){
            $sql .= " AND Fcategory LIKE '%{$request['category']}%' ";
        }
        if(!empty($request['begin_time'])){
            $sql .= " AND Fcreate_time >= '{$request['begin_time']}' ";
        }
        if(!empty($request['end_time'])){
            $sql .= " AND Fcreate_time <= '{$request['end_time']}' ";
        }

        return $sql;
    }

    public function search_goods($request){
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $params = $this->searchParams($request);

        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1 {$params} ORDER BY Fcreate_time DESC LIMIT ? , ?";

        list($offset, $limit) = parent::pageUtils($request);

        $ret = $this->db->query($s, [
            $offset,
            $limit
        ]);
        return $this->suc_out_put($ret->result('array'));
    }

    public function goods_delete($goods_id){

        return $this->suc_out_put();
    }

}