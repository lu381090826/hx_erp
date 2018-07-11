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
            $this->db->select('Fpos_name,Fid');
            $query = $this->db->get_where('pos',array("Fid"=>$data[$k]['pos_id']));
            $pos_data = $query->row_array();

            $data[$k]['pos_name'] = $pos_data['pos_name'];
            //查当天入库数量
            $date = date('Y-m-d',time());
            $date1 = date('Y-m-d ',strtotime('+1 day'));
            $sql = "select Fcount from t_storage_list_detail where Fsku_id='{$data[$k]['sku_id']}' and Fstatus='1' and Fenter_depot='{$data[$k]['depot_id']}' and Fstock_status='1' and Fstorage_date='{$date}' ";
            $query = $this->db->query($sql);
            $count_data = $query->result_array();
            
            //查仓库
            $this->db->select('Fdepot_name');
            $depot_name = $this->depot_model->get_depot($data[$k]['depot_id']);
            
            $data[$k]['depot_name'] = $depot_name['depot_name'];
            
            $date_count = '';
            foreach($count_data as $a=>$b){
                $date_count = $date_count+$count_data[$a]['count'];
            }
            $data[$k]['date_count'] = $date_count;
            if(!$data[$k]['date_count']){
                $data[$k]['date_count'] = '0';
            }
            
            //查询该sku_id未配数量
            $this->db->select('Fnum,Fsend_num');
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
            $this->db->select('Fpic');
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
    
    public function check_pos(){
        $depo_id = @$_REQUEST['depot_id'];
        $content = @$_REQUEST['content'];
    
        $sql = "select Fpos_name,Fid from t_pos where Fdid='{$depo_id}' and Fpos_name like '%{$content}%'";
        
        $check_data = $this->index_model->get_query($sql);

        return $check_data;
    }
    
    public function get_depot_count($sku_id){
        //查出指定skuid默认仓库的库存
        $query = $this->db->get_where('depot',array("Ftype"=>'1'));
        $moren_depot = $stock_data = $query->row_array();
        
        $query = $this->db->get_where('stock_list',array("Fsku_id"=>$sku_id,"Fdepot_id"=>$moren_depot['id']));
        $stock_data = $query->row_array();
        return $stock_data;
    }
    
    //查询条件获取 库存列表数据
    public function get_all_stock_where_list(){
        $page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $page_count = ($page-1)*15;
    
        $search_data  = array(
            "search"=>@$_REQUEST['search'],
            "depot_id"=>@$_REQUEST['depot_id']
        );
    
    
        $wherelist = array();
        if(!empty($search_data['search'])){
            $wherelist[] = "Fsku_id like '%{$search_data['search']}%'";
    
            if($search_data['depot_id']!==''){
                $wherelist[] = "Fdepot_id = '{$search_data['depot_id']}'";
            }
            //组装查询条件
            if(count($wherelist) > 0){
                $where = implode(' AND ' , $wherelist);
            }
            //判断查询条件
            $where = isset($where) ? $where : '';
    
            $get_count_sql = "select * from t_stock_list where {$where} ";
    
            $sql = $get_count_sql."order by Fcount desc limit {$page_count},15";
            
            
        }
        else{
            if($search_data['depot_id']!==''){
                $get_count_sql = "select * from t_stock_list where Fdepot_id='{$search_data['depot_id']}' ";
            }
            
            else{
                $get_count_sql = "select * from t_stock_list  ";
            }
            $sql = $get_count_sql."order by Fcount desc limit {$page_count},15";
    
        }

    
    
        //读取库存表
        $data = $this->index_model->get_query($sql);
    
         foreach($data as $k=>$v){
            
             //查这个仓库对应的库位
             $this->db->select('Fpos_name,Fid');
             $query = $this->db->get_where('pos',array("Fid"=>$data[$k]['pos_id']));
             $pos_data = $query->row_array();
             
             $data[$k]['pos_name'] = $pos_data['pos_name'];
             
            //查当天入库数量
            $date = date('Y-m-d',time());
            $date1 = date('Y-m-d ',strtotime('+1 day'));
            $sql = "select Fcount from t_storage_list_detail where Fsku_id='{$data[$k]['sku_id']}' and Fstatus='1' and Fenter_depot='{$data[$k]['depot_id']}' and Fstock_status='1' and Fstorage_date='{$date}' ";
            $query = $this->db->query($sql);
            $count_data = $query->result_array();
            
            //查仓库
            $depot_name = $this->depot_model->get_depot($data[$k]['depot_id']);
            
            $data[$k]['depot_id'] = $depot_name['depot_name'];
            $data[$k]['depot_name'] = $depot_name['depot_name'];
            
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
    
        $back_data = array(
            "stock_data"=>$data,
            "get_count_sql"=>$get_count_sql,
            'search_data'=>$search_data
        );
    
        return $back_data;
    }
    
    public function change_stock(){
        $id = @$_REQUEST['id'];
        $change_count = @$_REQUEST['change_count'];
        $total_count = @$_REQUEST['total_count'];
        
        $change_data = $change_count-$total_count;
        $this->db->where('Fid', $id);
        $this->db->set("Ftotal_count",$change_count);
        $this->db->set('Fcount','Fcount+'.$change_data,false);
        $this->db->update('stock_list');
        return true;
    }
}
