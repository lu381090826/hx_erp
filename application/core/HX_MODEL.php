<?php

class HX_MODEL extends CI_Model
{

    protected $total_num = 0;
    protected $offset = 0;
    protected $limit = 10;

    protected $m_input = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function suc_out_put($result = [])
    {
        $res['result'] = 0;
        $res['res_info'] = 'ok';
        $res['total_num'] = $this->total_num;
        $res['pages'] = (int)ceil($this->total_num / $this->limit);
        $res['result_rows'] = $result;

        return $res;
    }

    public function fail_out_put($code, $res_info)
    {
        $res['result'] = $code;
        $res['res_info'] = $res_info;
        return $res;
    }
}