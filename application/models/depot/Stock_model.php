<?php

class Stock_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('depot/depot_model');
    }
  
    //检测该sku是否存在该仓库的库存表中
    public function check_stock_sku_id($sku_id,$depot_id){
        $query = $this->db->get_where('stock_list',array("Fsku_id"=>$sku_id,"Fdepot_id"=>$depot_id));
        $data = $query->row_array();
        return $data ;
    }
    
    public function get_stock_list($page_number = 1){
        $page_count = ($page_number-1)*15;
        
        $this->db->order_by('Fcount', "DESC");
        $query = $this->db->get('stock_list','15',$page_count);
        $data = $query->result_array();
        
        foreach($data as $k=>$v){
            
            //查这个仓库对应的库位
            $query = $this->db->get_where('pos',array("Fdid"=>$data[$k]['depot_id']));
            $data[$k]['pos_data'] = $query->result_array();
            

            
            //查当天入库数量
            $date = date('Y-m-d',time());
            $date1 = date('Y-m-d ',strtotime('+1 day'));
            $sql = "select Fcount from t_storage_list_detail where Fsku_id='{$data[$k]['sku_id']}' and Fstatus='1' and Fenter_depot='{$data[$k]['depot_id']}' and Fstock_status='1' and Fstorage_date='{$date}' ";
            $query = $this->db->query($sql);
            $count_data = $query->result_array();
            
            //查仓库
            $depot_name = $this->depot_model->get_depot($data[$k]['depot_id']);
            
            $data[$k]['depot_id'] = $depot_name['depot_name'];
            
            $date_count = '';
            foreach($count_data as $a=>$b){
                $date_count = $date_count+$count_data[$a]['count'];
            }
            $data[$k]['date_count'] = $date_count;
            if(!$data[$k]['date_count']){
                $data[$k]['date_count'] = '0';
            }
            
            //查询该sku_id未配数量
            $query = $this->db->get_where('sell_allocate_item',array("Fsku_id"=>$data[$k]['sku_id'],"Fstatus !="=>'1'));
            $allocate_sku_id_data = $query->result_array();
            
            $weipei_count = '';
            foreach($allocate_sku_id_data as $a=>$b){
                $weipei_count = $weipei_count+$allocate_sku_id_data[$a]['num']-$allocate_sku_id_data[$a]['send_num'];
            }
            $data[$k]['weipei_count'] = $weipei_count;
            if(!$data[$k]['weipei_count']){
                $data[$k]['weipei_count'] = '0';
            }
            
            //查商品图片
            $query = $this->db->get_where('goods',array("Fgoods_id"=>$data[$k]['goods_id']));
            $goods_data = $query->row_array();
            
            $data[$k]['pic'] = $goods_data['pic'];
        }
        return $data ;
    }
    
    public function change_pos(){
        $id = @$_REQUEST['id'];
        $pos_id = @$_REQUEST['pos_id'];
        

        $this->db->set('Fpos_id', $pos_id);
        $this->db->where("Fid",$id);
        $this->db->update('stock_list');
        return true;
    }
}
