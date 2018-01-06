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
            //类名查重
            $ret = $this->get_by_name($check_res['Fcategory_name']);
            if (isset($ret['result_rows']) && count($ret['result_rows']) > 0) {
                show_error("分类名已重复");
            }
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
        $s = "SELECT Fid,Fpid,Fcategory_name FROM {$this->table} WHERE Fstatus = 1";

        $ret = $this->db->query($s);

        return $this->suc_out_put($ret->result('array'));
    }

    public function get_by_name($category_name)
    {
        $s = "SELECT Fid,Fpid,Fcategory_name FROM {$this->table} WHERE Fstatus = 1 AND Fcategory_name = ? LIMIT 1;";

        $ret = $this->db->query($s, $category_name);

        return $this->suc_out_put($ret->result('array'));
    }

    public function category_cache()
    {
        $arr = [];
        if ($this->config->item('redis_default')['cache_on']) {
            $category_cache = 'CATEGORY_CACHE';
            try {
                $this->load->driver('cache');
                if (empty($this->cache->redis->get($category_cache))) { //如果未设置
                    $arr = $this->getCategoryList();
                    foreach ($arr as $k => &$r) {
                        $r = $this->category_cache_series()[$k]['category_name'];
                    }
                    $this->cache->redis->save($category_cache, $arr, 86400); //设置
                } else {
                    $arr = $this->cache->redis->get($category_cache);  //从缓存中直接读取对应的值
                }

            } catch (Exception $e) {
                log_error($e->getMessage());
            }
        }

        if (empty($arr)) {
            $arr = $this->getCategoryList();
            foreach ($arr as $k => &$r) {
                $r = $this->category_cache_series()[$k]['category_name'];
            }
        }
        return $arr;
    }

    public $cate_list = [];

    public function showList(&$result, $level = 0)
    {
        foreach ($result as $k => &$row) {
            $row['type'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level) . '|-' . $row['category_name'];
            $this->cate_list[] = $row;

            if (!empty($row['children']) && count($row['children']) > 0) {
                $this->showList($row['children'], $level + 1);
            }
        }
        return $this->cate_list;
    }

    public function category_cache_tree()
    {
        $arr = [];
        if ($this->config->item('redis_default')['cache_on']) {
            $category_cache = 'CATEGORY_CACHE_TREE';
            try {
                $this->load->driver('cache');
                if (empty($this->cache->redis->get($category_cache))) { //如果未设置

                    $arr = $this->category_tree();

                    $this->cache->redis->save($category_cache, $arr, 86400 * 365); //设置一年
                } else {
                    $arr = $this->cache->redis->get($category_cache);  //从缓存中直接读取对应的值
                }

            } catch (Exception $e) {
                log_error($e->getMessage());
            }
        }

        if (empty($arr)) {
            $arr = $this->category_tree();
        }
        return $arr;
    }

    //系列缓存
    public function category_cache_series()
    {
        $arr = [];
        if ($this->config->item('redis_default')['cache_on']) {
            $category_cache = 'CATEGORY_CACHE_SERIES';
            try {
                $this->load->driver('cache');
                if (empty($this->cache->redis->get($category_cache))) { //如果未设置

                    $arr = $this->category_series();

                    $this->cache->redis->save($category_cache, $arr, 86400 * 365); //设置一年
                } else {
                    $arr = $this->cache->redis->get($category_cache);  //从缓存中直接读取对应的值
                }

            } catch (Exception $e) {
                log_error($e->getMessage());
            }
        }

        if (empty($arr)) {
            $arr = $this->category_series();
        }
        return $arr;
    }

    public function cache_delete()
    {
        if ($this->config->item('redis_default')['cache_on']) {
            $cache = 'CATEGORY_CACHE';
            $this->load->driver('cache');
            if ($this->cache->redis->get($cache) != null) {
                $this->cache->redis->delete($cache);
            }

            $cache = 'CATEGORY_CACHE_TREE';
            if ($this->cache->redis->get($cache) != null) {
                $this->cache->redis->delete($cache);
            }

            $cache = 'CATEGORY_CACHE_SERIES';
            if ($this->cache->redis->get($cache) != null) {
                $this->cache->redis->delete($cache);
            }
        }

    }

    private function category_tree()
    {
        $list = $this->get_all_list()['result_rows'];
        $arr = [];
        foreach ($list as $r) {
            $arr[$r['id']] = $r;
        }

        $tree = [];
        foreach ($arr as $r) {
            if ($r['pid'] != 0) {
                if (isset($tree[$r['pid']])) {
                    $tree[$r['pid']]['childs'][] = $r;
                }
            } else {
                $tree[$r['id']] = $r;
                $tree[$r['id']]['childs'] = [];
            }
        }
        return $tree;
    }

    private function category_series()
    {
        $arr = $this->get_all_list()['result_rows'];
        $result = [];
        foreach ($arr as $r) {
            $result[$r['id']] = $r;
        }
        foreach ($result as &$r) {
            if ($r['pid'] != 0) {
                $r['category_name'] = $result[$r['pid']]['category_name'] . " / " . $r['category_name'];
            }
        }
        return $result;
    }

    public function category_series_tree()
    {
        $list = $this->get_all_list()['result_rows'];

        $tree = [];
        $this->getNodeTree($list, $tree);

        return $tree;
    }

    function getNodeTree(&$list, &$tree, $pid = 0)
    {
        $tree = [];
        foreach ($list as $key => $value) {
            if ($pid == $value['pid']) {
                $tree[$value['id']] = $value;
                unset($list[$key]);
                self::getNodeTree($list, $tree[$value['id']]['children'], $value['id']);
                reset($list);
            }
        }
    }

    function getNodeLevel(&$tree, &$res, $pid = 0, $level = 0)
    {
        foreach ($tree as $k => $r) {
            $tree[$k]['level'] = $level;
            $res[] = $tree[$k];
            if ($r['pid'] == $pid) {
                if (count($r['children']) > 0) {
                    self::getNodeLevel($tree[$k]['children'], $res, $r['id'], ++$level);
                }
            }
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
        $s = "SELECT Fid,Fcategory_name FROM {$this->table} WHERE Fstatus = 1 AND Fpid = {$id}";
        $ret = $this->db->query($s);
        if (!empty($ret->result('array')) && count($ret->result('array')) > 0) {
            json_ajax_out_put(500, "存在子类，需先删除子类");
        } else {
            $this->cache_delete();
            return $this->db->update($this->table, ['Fstatus' => 0, 'Fop_uid' => $this->session->uid], ['Fid' => $id]);
        }
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

    public function update_name($id, $name)
    {
        return $this->db->update($this->table, ['Fcategory_name' => $name, 'Fop_uid' => $this->session->uid], ['Fid' => $id]);
    }
}