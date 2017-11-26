<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Sku_model extends HX_Model
{


    private $table = "t_sku";
    private $table_prefixes = "F";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function sku_delete_by_id($sku_id)
    {
        return $this->db->update($this->table, ['Fstatus' => 0, 'Fop_uid' => $this->session->uid], ['Fsku_id' => $sku_id]);
    }

    public function get_sku_list_by_goods_id($goods_id)
    {
        $s = "SELECT Fsku_id FROM {$this->table} WHERE Fgoods_id = ? AND Fstatus = 1  ORDER BY Fcreate_time DESC";
        $ret = $this->db->query($s, [$goods_id]);
        $sku_list = [];
        foreach ($ret->result('array') as $row) {
            array_push($sku_list, $row['sku_id']);
        }
        return $this->suc_out_put($sku_list);
    }

    public function get_sku_list_info_by_goods_id($goods_id)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fgoods_id = ? AND Fstatus = 1  ORDER BY Fmodify_time DESC";
        $ret = $this->db->query($s, [$goods_id]);
        $sku_list = [];

        $this->load->model('goods/color_model', 'color_m');
        $color_cache = $this->color_m->color_cache();

        $this->load->model('goods/size_model', 'size_m');
        $size_cache = $this->size_m->size_cache();

        $this->load->model('goods/category_model', 'category_m');
        $category_cache = $this->category_m->category_cache();

        foreach ($ret->result('array') as $row) {
            if (!isset($color_cache[$row['color_id']]) || !isset($size_cache[$row['size_id']]) || !isset($category_cache[$row['category_id']])) {
                continue;
            }
            $row['color_info'] = $color_cache[$row['color_id']];
            $row['size_info'] = $size_cache[$row['size_id']];
            $row['category_info'] = $category_cache[$row['category_id']];
            array_push($sku_list, $row);
        }
        return $this->suc_out_put($sku_list);
    }

    public function get_sku_list($page = 1)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  ORDER BY Fcreate_time DESC LIMIT ? , ?";
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

    private function search_params($request)
    {

        $sql = "";
        if (!empty($request['sku_id'])) {
            $sql .= " AND Fsku_id LIKE '%{$request['sku_id']}%' ";
        }

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

        if (!empty($request['year'])) {
            $sql .= " AND Fyear = '{$request['year']}' ";
        }
        if (!empty($request['month'])) {
            $sql .= " AND Fmonth = '{$request['month']}' ";
        }
        if (!empty($request['sex'])) {
            $sql .= " AND Fsex = '{$request['sex']}' ";
        }
        if (!empty($request['season'])) {
            $sql .= " AND Fseason = '{$request['season']}' ";
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

    public function search_sku($page = 1, $request = [])
    {
        $params = "WHERE Fstatus = 1";
        if (!empty($request)) {
            $params = $this->search_params($request);
        }

        $s = "SELECT * FROM {$this->table} {$params}";
        $ret = $this->db->query($s);
        $this->total_num = $ret->num_rows();

        $s = "SELECT * FROM {$this->table} {$params}  ORDER BY Fcreate_time DESC LIMIT ? , ?";
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

    public function check_product_number_available($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fproduct_number = ? ;";

        $ret = $this->db->query($s, [
            $request['product_number']
        ]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }

    private function insert_sku_pararm($request)
    {
        $params = [];
        if (isset($request['goods_id']) && isset($request['color']) && isset($request['size'])) {
            $params = $this->get_sku_id($request, $request['color'], $request['size']);
        } else {
            show_error('缺少颜色或尺码');
        }
        $request['op_uid'] = $this->session->uid;
        return $params;
    }

    public function unset_sku($goods_id)
    {
        $this->db->update($this->table, ["Fstatus" => 0], ["Fgoods_id" => $goods_id]);
    }

    private function get_sku_id($request, $color_id_array, $size_id_array)
    {
        $sku_info = [];
        $i = 0;
        foreach ($color_id_array as $k => $color_row) {
            foreach ($size_id_array as $z => $size_row) {

                $curr_sku_id = $request['goods_id'] . str_pad($color_row, 2, "0", STR_PAD_LEFT) . str_pad($size_row, 2, "0", STR_PAD_LEFT);

                $sku_info[$i]['Fsku_id'] = $curr_sku_id;
                $sku_info[$i]['Fgoods_id'] = $request['goods_id'];
                $sku_info[$i]['Fcolor_id'] = $color_row;
                $sku_info[$i]['Fsize_id'] = $size_row;


                if (isset($request['cost'])) {
                    $sku_info[$i]['Fcost'] = $request['cost'];
                }
                if (isset($request['price'])) {
                    $sku_info[$i]['Fprice'] = $request['price'];
                }
                if (isset($request['pic'])) {
                    $sku_info[$i]['Fpic'] = $request['pic'];
                }
                if (isset($request['record_number'])) {
                    $sku_info[$i]['Frecord_number'] = $request['record_number'];
                }
                if (isset($request['brand'])) {
                    $sku_info[$i]['Fbrand'] = $request['brand'];
                }
                if (isset($request['category_id'])) {
                    $sku_info[$i]['Fcategory_id'] = $request['category_id'];
                }
                if (isset($request['category'])) {
                    $sku_info[$i]['Fcategory'] = $request['category'];
                }
                if (isset($request['sex'])) {
                    $sku_info[$i]['Fsex'] = $request['sex'];
                }
                if (isset($request['year'])) {
                    $sku_info[$i]['Fyear'] = $request['year'];
                }
                if (isset($request['month'])) {
                    $sku_info[$i]['Fmonth'] = $request['month'];
                }
                if (isset($request['season'])) {
                    $sku_info[$i]['Fseason'] = $request['season'];
                }
                if (isset($request['memo'])) {
                    $sku_info[$i]['Fmemo'] = $request['memo'];
                }
                if (isset($request['op_uid'])) {
                    $sku_info[$i]['Fop_uid'] = $this->session->uid;
                }
                if (isset($request['status'])) {
                    $sku_info[$i]['Fstatus'] = $request['status'];
                } else {
                    $sku_info[$i]['Fstatus'] = 1;
                }
                $i++;
            }
        }
        return $sku_info;
    }

    public function modify_sku($request)
    {
        //所有sku status状态置为0，再增加
        $this->unset_sku($request['goods_id']);
        $params = $this->insert_sku_pararm($request);
        foreach ($params as $p) {
            $this->db->replace($this->table, $p);
        }
    }

    public function get_row_by_id($uid)
    {
        $s = "SELECT * FROM {$this->table} u  WHERE u.Fstatus = 1 AND Fid = ? LIMIT 1;";
        $ret = $this->db->query($s, [$uid]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }

    public function get_by_sku_id($sku_id)
    {
        $s = "SELECT * FROM {$this->table} u  WHERE u.Fstatus = 1 AND Fsku_id = ? LIMIT 1;";
        $ret = $this->db->query($s, [$sku_id]);
        return $this->suc_out_put($ret->row(0, 'array'));
    }
}