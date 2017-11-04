<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Color_model extends HX_Model
{

    private $table = "t_color";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function color_delete_by_id($id)
    {
        $this->color_cache_delete();
        return $this->db->update($this->table, ['Fstatus' => 0], ['Fid' => $id]);
    }

    public function get_color_list_all()
    {
        $s = "SELECT Fid,Fname,Fcolor_num,Fcolor_code FROM {$this->table} WHERE Fstatus = 1";
        $ret = $this->db->query($s);

        return $this->suc_out_put($ret->result('array'));
    }

    public function get_color_list($page = 1)
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

    public function check_color_num_available($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fcolor_num = ? ;";

        $ret = $this->db->query($s, [
            $request['color_num']
        ]);
        if (!$ret->row(0)) {
            return $this->suc_out_put();
        }
        return $this->fail_out_put(1000, "颜色代码已存在");
    }

    private function insert_color_check($request)
    {
        $msg = "";
        if (!isset($request['name'])) {
            $msg = "颜色名不能为空";
        }
        if (!isset($request['color_num'])) {
            $msg = "颜色代码不能为空";
        }
        if (!isset($request['color_code'])) {
            $msg = "颜色代码不能为空";
        }

        $ret = $this->check_color_num_available($request);
        if ($ret['result'] != 0) {
            $msg = $ret['res_info'];
        }
        if ($msg)
            show_error($msg);
    }

    public function insert_color($request)
    {
        $this->insert_color_check($request);

        $insert_arr = [
            'Fname' => $request['name'],
            'Fcolor_num' => $request['color_num'],
            'Fcolor_code' => $request['color_code'],
            'Fmemo' => $request['memo'],
        ];
        $this->db->insert($this->table, $insert_arr);
        $this->color_cache_delete();
    }

    public function color_cache_delete()
    {
        $color_cache = 'COLOR_CACHE';
        $this->load->driver('cache');
        if ($this->cache->redis->get($color_cache) != null) { //如果未设置
            $this->cache->redis->delete($color_cache);
        }
    }

    public function color_cache()
    {
        error_reporting(0);
        $color_cache = 'COLOR_CACHE';
        try {
            $this->load->driver('cache');
            if (empty($this->cache->redis->get($color_cache))) { //如果未设置

                $arr = $this->colorList();

                $this->cache->redis->save($color_cache, $arr, 86400); //设置
            } else {
                $arr = $this->cache->redis->get($color_cache);  //从缓存中直接读取对应的值
            }

        } catch (Exception $e) {
            log_error($e->getMessage());
        }

        if (!isset($arr)) {
            $arr = $this->colorList();
        }

        return $arr;
    }

    private function colorList()
    {
        $arr = $this->get_color_list_all();
        $result = [];
        foreach ($arr['result_rows'] as $r) {
            $result[$r['id']] = $r;
        }
        return $result;
    }

}