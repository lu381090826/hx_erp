<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Size_model extends HX_Model
{

    private $table = "t_size";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function size_delete_by_id($id)
    {
        return $this->db->update($this->table, ['Fstatus' => 0], ['Fid' => $id]);
    }

    public function get_size_list_all()
    {
        $s = "SELECT Fid,Fsize_info,Fsize_num FROM {$this->table} WHERE Fstatus = 1";

        $ret = $this->db->query($s);

        return $this->suc_out_put($ret->result('array'));
    }

    public function get_size_list($page = 1)
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

    public function check_size_num_available($request)
    {
        $s = "SELECT * FROM {$this->table} WHERE Fstatus = 1  AND Fsize_num = ? ;";

        $ret = $this->db->query($s, [
            $request['size_num']
        ]);
        if (!$ret->row(0)) {
            return $this->suc_out_put();
        }
        return $this->fail_out_put(1000, "尺码代码已存在");
    }

    private function insert_size_check($request)
    {
        $msg = "";
        if (!isset($request['size_info'])) {
            $msg = "尺码信息不能为空";
        }
        if (!isset($request['size_num'])) {
            $msg = "尺码代码不能为空";
        }

        $ret = $this->check_size_num_available($request);
        if ($ret['result'] != 0) {
            $msg = $ret['res_info'];
        }
        if ($msg)
            show_error($msg);
    }

    public function insert_size($request)
    {
        $this->insert_size_check($request);

        $insert_arr = [
            'Fsize_info' => $request['size_info'],
            'Fsize_num' => $request['size_num'],
            'Fmemo' => $request['memo'],
        ];
        $this->db->insert($this->table, $insert_arr);
    }

    public function size_cache()
    {
        error_reporting(0);
        $cache = 'SIZE_CACHE';
        try {
            $this->load->driver('cache');
            if ($this->cache->redis->get($cache) === null) { //如果未设置

                $arr = $this->getSizeList();
                log_message('INFO', json_encode($arr, JSON_UNESCAPED_UNICODE));

                $this->cache->redis->save($cache, $arr); //设置
                $this->cache->redis->EXPIRE($cache, 86400); //设置过期时间 （1天）
            } else {
                $arr = $this->cache->redis->get($cache);  //从缓存中直接读取对应的值
            }

        } catch (Exception $e) {
            log_message('ERROR', $e->getMessage());
        }

        if (!isset($arr)) {
            $arr = $this->getSizeList();
        }

        return $arr;
    }

    public function cache_delete()
    {
        $cache = 'SIZE_CACHE';
        $this->load->driver('cache');
        if ($this->cache->redis->get($cache) != null) { //如果未设置
            $this->cache->redis->delete($cache);
        }
    }

    private function getSizeList(){
        $arr = $this->get_size_list_all();
        $result = [];
        foreach ($arr['result_rows'] as $r) {
            $result[$r['id']] = $r;
        }
        return $result;
    }
}