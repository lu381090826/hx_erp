<?php

class Api_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('depot/stock_model');
    }

    public function get_sku_id_data($sku_id){
        
        if(!$sku_id){
            echo json_encode(array("result"=>0,"msg"=>"sku_id不能为空！"));exit;
        }
        //查出该sku_id已配多少和默认仓库库存
        $stock_data =  $this->stock_model->get_depot_count($sku_id);
        
        
        //查询该sku_id未配数量
        $query = $this->db->get_where('sell_allocate_item',array("Fsku_id"=>$sku_id,"Fstatus !="=>'1'));
        $allocate_sku_id_data = $query->result_array();
        
        $weipei_count = '';
        foreach($allocate_sku_id_data as $a=>$b){
            $weipei_count = $weipei_count+$allocate_sku_id_data[$a]['num']-$allocate_sku_id_data[$a]['send_num'];
        }
        $data['weipei_count'] = $weipei_count;
        if(!$data['weipei_count']){
            $data['weipei_count'] = '0';
        }
        
        //已配
        $data['send_count'] = $stock_data['send_count'];
        if(!$data['send_count']){
            $data['send_count'] = '0';
        }
        //库存
        $data['count'] = $stock_data['count'];
        if(!$data['count']){
            $data['count'] = '0';
        }
        
        return $data;
    }
}
