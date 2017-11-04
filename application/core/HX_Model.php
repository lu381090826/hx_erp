<?php

class HX_Model extends CI_Model
{

    protected $total_num = 0;
    protected $offset = 0;
    protected $limit = 10;
    protected $reentry = null; //是否重入
    protected $reentry_key = 0; //重入主键

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

    protected function get_offset($page)
    {
        if ($page < 1) {
            $page = 1;
        }
        return $this->offset + ($page - 1) * $this->limit;
    }

    /**
     * @param $request
     * @return array
     */
    protected function pageUtils($request)
    {
        if (!isset($request['page']) || $request['page'] < 1) {
            $page = 1;
        } else {
            $page = $request['page'];
        }
        $offset = $this->offset + ($page - 1) * $this->limit;
        $limit = isset($request['limit']) ? $request['limit'] : 10;
        return array($offset, $limit);
    }
}