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
        $this->cache_delete();
    }

    public function get_all_list()
    {
        $s = "SELECT Fid,Fcategory_name FROM {$this->table} WHERE Fstatus = 1";

        $ret = $this->db->query($s);

        return $this->suc_out_put($ret->result('array'));
    }

    public function category_cache()
    {
        error_reporting(0);
        $category_cache = 'CATEGORY_CACHE';
        try {
            $this->load->driver('cache');
            if (empty($this->cache->redis->get($category_cache))) { //如果未设置
                $arr = $this->getCategoryList();

                $this->cache->redis->set($category_cache, $arr); //设置
                $this->cache->redis->EXPIRE($category_cache, 86400); //设置过期时间 （1天）
            } else {
                $arr = $this->cache->redis->get($category_cache);  //从缓存中直接读取对应的值
            }

        } catch (Exception $e) {
            log_error($e->getMessage());
        }

        if (!isset($arr)) {
            $arr = $this->getCategoryList();
        }
        log_out($arr);
        return $arr;
    }

    public function cache_delete()
    {
        $cache = 'CATEGORY_CACHE';
        $this->load->driver('cache');
        if ($this->cache->redis->get($cache) != null) { //如果未设置
            $this->cache->redis->delete($cache);
        }
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
        $this->cache_delete();
        return $this->db->update($this->table, ['Fstatus' => 0, 'Fop_uid' => $this->session->uid], ['Fid' => $id]);
    }

    /**
     * @param $arr
     * @return mixed
     */
    private function getCategoryList()
    {
        $arr = [];
        $s = "SELECT Fid,Fcategory_name FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);

        foreach ($ret->result('array') as $row) {
            $arr[$row['id']] = $row['category_name'];
        }
        return $arr;
    }
}