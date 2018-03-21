<?php

class Offer_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('depot/depot_model');
    }

    
    public function get_offer_list($page_number = 1){
        $page_count = ($page_number-1)*15;
        
        $this->db->order_by('Fid', 'DESC');
        $query = $this->db->get('sell_allocate_item','15',$page_count);
        $data = $query->result_array();
        
        foreach($data as $k=>$v){
        
            //查销售单号
            $query = $this->db->get_where('sell_order',array("Fid"=>$data[$k]['order_id']));
            $order_data = $query->row_array();            
            
            //查员工信息
            $query = $this->db->get_where('user',array("Fuid"=>$order_data['user_id']));
            $user_data = $query->row_array();
            
            //查客户信息
            $query = $this->db->get_where('client',array("Fid"=>$order_data['client_id']));
            $client_data = $query->row_array();
            
            
            $data[$k]['order_num'] = $order_data['order_num'];
            $data[$k]['user_name'] = $user_data['name'];
            $data[$k]['client_phone'] = $client_data['phone'];
            $data[$k]['client_name'] = $client_data['name'];
            
            //查报货单号和报货时间
            $query = $this->db->get_where('sell_allocate',array("Fid"=>$data[$k]['allocate_id']));
            $allocate_data = $query->row_array();
            
            $data[$k]['allocate_num'] = $allocate_data['order_num'];
            $data[$k]['create_at'] = date("Y-m-d H:i:s",$allocate_data['create_at']);
            
            //查款号
            $query = $this->db->get_where('sell_order_spu',array("Fid"=>$data[$k]['order_spu_id']));
            $spu_data = $query->row_array();

            $data[$k]['spu'] = $spu_data['spu_id'];
            
            //查颜色 尺码
            $query = $this->db->get_where('sell_order_sku',array("Fid"=>$data[$k]['order_sku_id']));
            $sku_data = $query->row_array();
            
            $data[$k]['color'] = $sku_data['color'];
            $data[$k]['size'] = $sku_data['size'];
            $data[$k]['sku_id'] = $sku_data['sku_id'];
            
            //查库存
            $query = $this->db->get_where('stock_list',array("Fsku_id"=>$sku_data['sku_id']));
            $stock_data = $query->row_array();
            
            $data[$k]['stock_count'] = $stock_data['count'];
            if(!$data[$k]['stock_count']){
                $data[$k]['stock_count'] = '0';
            }
            //已配
            $data[$k]['send_count'] = $stock_data['send_count'];
            
            //查库位
            $query = $this->db->get_where('pos',array("Fid"=>$stock_data['pos_id']));
            $pos_data = $query->row_array();
            
            $data[$k]['pos_name'] = $pos_data['pos_name'];
            
            //查询该sku_id未配数量
            $query = $this->db->get_where('sell_allocate_item',array("Fsku_id"=>$data[$k]['sku_id'],"Fstatus !="=>'1'));
            $allocate_sku_id_data = $query->result_array();
            
            $weipei_count = '';
            foreach($allocate_sku_id_data as $a=>$b){
                $weipei_count = $weipei_count+$allocate_sku_id_data[$a]['num']-$allocate_sku_id_data[$a]['send_num'];
            }
            $data[$k]['weipei_count'] = $weipei_count;
            
            //查询该sku的报货单号对应的配货情况（待开发）
//             $query = $this->db->get_where('stock_list',array("Fsku_id"=>$sku_data['sku_id']));
//             $stock_data = $query->row_array();
        
        }
        
        return $data ;
    }

    public function get_offer(){

        $id_list = @$_GET['id_list'];
        
        $sql = "select * from t_sell_allocate_item where Fid in({$id_list})";
        $query = $this->db->query($sql);
        $data = $query->result_array();

        
        $back_data = array();
        foreach($data as $k=>$v){   
            //查销售单号
            $query = $this->db->get_where('sell_order',array("Fid"=>$data[$k]['order_id']));
            $order_data = $query->row_array();
            
            //查报货单号和报货时间
            $query = $this->db->get_where('sell_allocate',array("Fid"=>$data[$k]['allocate_id']));
            $allocate_data = $query->row_array();
            
            //查款号
            $query = $this->db->get_where('sell_order_spu',array("Fid"=>$data[$k]['order_spu_id']));
            $spu_data = $query->row_array();           
            
            //查颜色 尺码
            $query = $this->db->get_where('sell_order_sku',array("Fid"=>$data[$k]['order_sku_id']));
            $sku_data = $query->row_array();
            

            //查库存
            $query = $this->db->get_where('stock_list',array("Fsku_id"=>$sku_data['sku_id']));
            $stock_data = $query->row_array();
            
            $back_data[$k]['Fstock_count'] = $stock_data['count'];
            if(!$back_data[$k]['Fstock_count']){
                $back_data[$k]['Fstock_count'] = '0';
            }
            
            $back_data[$k]['Fsell_order'] = $order_data['order_num'];
            $back_data[$k]['Fallocate_order'] = $allocate_data['order_num'];
            
            $back_data[$k]['Fcolor'] = $sku_data['color'];
            $back_data[$k]['Fsize'] = $sku_data['size'];
            
            $back_data[$k]['Fspu'] = $spu_data['spu_id'];
            $back_data[$k]['Fsku_id'] = $sku_data['sku_id'];
            $back_data[$k]['Fallocate_count'] = $data[$k]['num'];
            $back_data[$k]['Fsid'] = $data[$k]['id'];
            $back_data[$k]['Fsend_num'] = $data[$k]['send_num'];
        }
        return $back_data ;
    }
    
    public function add_odo(){
        $data['Fname'] = $name = @$_REQUEST['name'];
        $data['Fenter_depot'] = $enter_depot = @$_REQUEST['enter_depot'];
        $data['Fsource_depot'] = $source = @$_REQUEST['source_depot'];
        $data['Fodo_sn'] = $odo_sn = @$_REQUEST['odo_sn'];
        $data['Fbeizhu'] = $beizhu = @$_REQUEST['beizhu'];
        $data['Fodo_date'] = $odo_date = @$_REQUEST['odo_date'];
        
        $data['Fcreate_time'] = date("Y-m-d H:i:s",time());
        $data['Fstatus'] = '4';
        
        $get_data = json_decode(@$_REQUEST['data'],true);
        
        $send_count_json =json_decode(@$_REQUEST['send_count_json'],true);
        
        $total_count  = '';
        foreach($get_data as $k=>$v){
            
            if($send_count_json[$k]>$get_data[$k]['Fallocate_count']-$get_data[$k]['Fsend_num']){
                echo json_encode(array("result"=>0,"msg"=>"出库数量不能大于报货数量"));exit;
            }
            $get_data[$k]['Fodo_sn'] = $odo_sn;
            $get_data[$k]['Fsend_count'] = $send_count_json[$k];
            
            $total_count = $total_count+$send_count_json[$k];
        }
        $data['Ftotal_count'] = $total_count;
        
        //添加出库单
        $this->db->insert('odo', $data);
        $id = $this->db->insert_id();
        
        //添加出库详情
        foreach($get_data as $k=>$v){
            
            unset($get_data[$k]['Fstock_count']);
            //unset($get_data[$k]['Fid']);
            unset($get_data[$k]['Fsend_num']);
            
            $this->db->insert('odo_detail', $get_data[$k]);
            $id = $this->db->insert_id();
            
            //减可用库存 增加已配数量                公式   可用库存 = 总库存-已配数量
            $this->db->set('Fcount','Fcount-'.$get_data[$k]['Fsend_count'],false);
            $this->db->set('Fsend_count','Fsend_count+'.$get_data[$k]['Fsend_count'],false);
            $this->db->where('Fsku_id',$get_data[$k]['Fsku_id']);
            $this->db->update('stock_list');
            
            
            //查找销售单号对应的id
            $query = $this->db->get_where('sell_order',array("Forder_num"=>$get_data[$k]['Fsell_order']));
            $order_data = $query->row_array();
            
            //查找报货号对应的id
            $query = $this->db->get_where('sell_allocate',array("Forder_num"=>$get_data[$k]['Fallocate_order']));
            $allocate_data = $query->row_array();
            
            //在报货表sell_allocate_item增加已配数量
            $this->db->set('Fsend_num','Fsend_num+'.$get_data[$k]['Fsend_count'],false);
            $this->db->where('Forder_id',$order_data['id']);
            $this->db->where('Fallocate_id',$allocate_data['id']);
            $this->db->where('Fsku_id',$get_data[$k]['Fsku_id']);
            $this->db->update('sell_allocate_item');
        }
        
        return true;
    }
    
    public function get_odo_list($page_number = 1){
        $page_count = ($page_number-1)*15;
    
        $this->db->order_by('Fid', 'DESC');
        $query = $this->db->get('odo','15',$page_count);
        $data = $query->result_array();
    
        foreach($data as $k=>$v){
            
             //查出库仓库
            $query = $this->db->get_where('depot',array("Fid"=>$data[$k]['source_depot']));
            $depot_data = $query->row_array();
            $data[$k]['source_depot'] = $depot_data['depot_name'];
            
            if($data[$k]['enter_depot']==1){
                $data[$k]['enter_depot'] = '销售出库';
            }
            else{
                $data[$k]['enter_depot'] = '其他出库';
            }
            
//             switch ($data[$k]['status']){
//                 case"4":
//                     $data[$k]['status'] = '等待打单';
//                 break;
                
//                 case"3":
//                     $data[$k]['status'] = '打单完成';
//                 break;
                
//                 case"2":
//                     $data[$k]['status'] = '拣货完成';
//                 break;
                    
//                 case"1":
//                     $data[$k]['status'] = '订单完成';
//                 break;
                
//             }
            
        }
    
        return $data ;
    }
    
    public function get_odo_detail($odo_sn){
    
        $query = $this->db->get_where('odo',array("Fodo_sn"=>$odo_sn));
        $odo_data = $query->row_array();
    
        //查仓库名字
        $query = $this->db->get_where('depot',array("Fid"=>$odo_data['source_depot']));
        $depot_data = $query->row_array();
        
        $odo_data['depot_name'] = $depot_data['depot_name'];
        
        $query = $this->db->get_where('odo_detail',array("Fodo_sn"=>$odo_sn));
        $odo_detail_data = $query->result_array();
        
        foreach($odo_detail_data as $k=>$v){
    
            //查实时库存
            $query = $this->db->get_where('stock_list',array("Fsku_id"=>$odo_detail_data[$k]['sku_id']));
            $stock_data = $query->row_array();
            $odo_detail_data[$k]['stock_count'] = $stock_data['count'];
     
            //查库位
            $query = $this->db->get_where('pos',array("Fid"=>$stock_data['pos_id']));
            $pos_data = $query->row_array();
            
            $odo_detail_data[$k]['pos_name'] = $pos_data['pos_name'];
            
            //查出已配多少
            $query = $this->db->get_where('sell_allocate_item',array("Fid"=>$odo_detail_data[$k]['sid']));
            $sell_allocate_item_data = $query->row_array();
            
            $odo_detail_data[$k]['send_num'] = $sell_allocate_item_data['send_num'];
            if(!$odo_detail_data[$k]['send_num']){
                $odo_detail_data[$k]['send_num'] = '0';
            }
        }
        $back_data = array(
            "odo_data"=>$odo_data,
            "odo_detail_data"=>$odo_detail_data
        );
        return $back_data ;
    }
    
    public function update_odo_status($status,$odo_sn){
        
        //先查出该订单状态先
        $query = $this->db->get_where('odo',array("Fodo_sn"=>$odo_sn));
        $odo_data = $query->row_array();
        
        if($status!=3){
            switch ($odo_data['status']){
                case"4":
                    if($status!='3'){
                        echo json_encode(array("result"=>0,"msg"=>"请先点击打单按钮"));exit;
                    }
                 break;
            
                case"3":
                    if($status!='2'){
                        echo json_encode(array("result"=>0,"msg"=>"请先点击拣货完成按钮！"));exit;
                    }
                    break;
            
                case"2":
                    if($status!='1'){
                        echo json_encode(array("result"=>0,"msg"=>"该订单已经拣货完成，请点击出库按钮！"));exit;
                    }
                    else{
                        
                          //查询该出库单所有的出库详情
                        $query = $this->db->get_where('odo_detail',array("Fodo_sn"=>$odo_sn));
                        $odo_detail_data = $query->result_array();
                                              
                        foreach($odo_detail_data as $k=>$v){ 
                            //出库完成 减少该skuid总库存和已配数量   增加总出库数量
                            $this->db->set('Ftotal_count','Ftotal_count-'.$odo_detail_data[$k]['send_count'],false);
                            $this->db->set('Fsend_count','Fsend_count-'.$odo_detail_data[$k]['send_count'],false);
                            $this->db->set('Fout_count','Fout_count+'.$odo_detail_data[$k]['send_count'],false);
                            $this->db->where('Fsku_id',$odo_detail_data[$k]['sku_id']);
                            $this->db->update('stock_list');
                            
                        }
                    }
                break;
            
                case"1":
                    echo json_encode(array("result"=>0,"msg"=>"该订单已经完成出库！"));exit;
                break;
            }
            $this->db->set('Fstatus',$status);
            $this->db->where('Fodo_sn',$odo_sn);
            $this->db->update('odo');
        }
        elseif($status==3){
            if($odo_data['status']==4){
                $this->db->set('Fstatus',$status);
                $this->db->where('Fodo_sn',$odo_sn);
                $this->db->update('odo');
            }
        }
        $query = $this->db->get_where('odo',array("Fodo_sn"=>$odo_sn));
        $odo_data = $query->row_array();
        
                    switch ($odo_data['status']){
                        case"4":
                            $odo_data['status'] = '等待打单';
                        break;
        
                        case"3":
                            $odo_data['status'] = '打单完成';
                        break;
        
                        case"2":
                            $odo_data['status'] = '拣货完成';
                        break;
        
                        case"1":
                            $odo_data['status'] = '已出库';
                            
                        break;
        
        }
        
        return $odo_data['status'];
    }
}
