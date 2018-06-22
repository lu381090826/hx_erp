<?php

class Offer_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('depot/depot_model');
        $this->load->model('depot/stock_model');
    }

    
    public function get_offer_list($page_number = 1){
        $page_count = ($page_number-1)*15;
        
        $this->db->order_by('Fid', 'DESC');
        $query = $this->db->get('sell_allocate_item','15',$page_count);
        $data = $query->result_array();
        
        foreach($data as $k=>$v){
        
            //查销售单号、付款方式、配货方式、收货方式等等
            $query = $this->db->get_where('sell_order',array("Fid"=>$data[$k]['order_id']));
            $order_data = $query->row_array();            
            
            //查员工信息
            $query = $this->db->get_where('user',array("Fuid"=>$order_data['user_id']));
            $user_data = $query->row_array();
            
            //查客户信息
            $query = $this->db->get_where('client',array("Fid"=>$order_data['client_id']));
            $client_data = $query->row_array();
            
            switch ($order_data){
                
            }
            $data[$k]['order_num'] = $order_data['order_num'];
            $data[$k]['user_name'] = $user_data['name'];
            $data[$k]['client_phone'] = $client_data['phone'];
            $data[$k]['client_name'] = $client_data['name'];
            
            //收货方式
            $data[$k]['delivery_type'] = $this->get_delivery_type($order_data['delivery_type']);

            
            //付款方式
            $data[$k]['payment'] = $this->get_payment($order_data['payment']);
           
            //配货方式
            $data[$k]['allocate_mode'] = $this->get_allocate_mode($order_data['allocate_mode']);
            
            //销售单开单时间
            $data[$k]['create_at'] = date("Y-m-d H:i:s",$order_data['create_at']);
            
            //查报货单号和报货时间
            $query = $this->db->get_where('sell_allocate',array("Fid"=>$data[$k]['allocate_id']));
            $allocate_data = $query->row_array();
            
            $data[$k]['allocate_num'] = $allocate_data['order_num'];
            $data[$k]['allocate_create_at'] = date("Y-m-d H:i:s",$allocate_data['create_at']);
            
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

            //查出默认仓库的库存
            $stock_data = $this->stock_model->get_depot_count($sku_data['sku_id']);
      
            $data[$k]['stock_count'] = $stock_data['count'];
            if(!$data[$k]['stock_count']){
                $data[$k]['stock_count'] = '0';
            }
            
            //已配
            $data[$k]['send_count'] = $stock_data['send_count'];
            
            if(!$data[$k]['send_count']){
                $data[$k]['send_count'] = '0';
            }
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

    //手动审核
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
            
            //手动审核支持者定制的配货方式
//             if($order_data['allocate_mode']!='1'){
//                 echo "<script>alert('自动审核只支持定制配货方式');history.go('-1');</script>";exit;
//             }

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
            
            //查库位
            $query = $this->db->get_where('pos',array("Fid"=>$stock_data['pos_id']));
            $pos_data = $query->row_array();
            
            //查客户信息
            $query = $this->db->get_where('client',array("Fid"=>$order_data['client_id']));
            $client_data = $query->row_array();
            
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
            $back_data[$k]['Forder_id'] = $data[$k]['order_id'];
            $back_data[$k]['Fsend_num'] = $data[$k]['send_num'];
            $back_data[$k]['Fclient_id'] = $order_data['client_id'];
            $back_data[$k]['Fshop_id'] = $order_data['shop_id'];
            
            $back_data[$k]['Fclient_name'] = $client_data['name'];
            $back_data[$k]['Fclient_phone'] = $client_data['phone'];
            $back_data[$k]['Fpos_name'] = $pos_data['pos_name'];
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
            
            //配货数量必须为正整数
             $check_num = preg_match("/^[1-9][0-9]*$/",$send_count_json[$k]);
             
            if(!$check_num){
               echo json_encode(array("result"=>0,"msg"=>"请输入正整数"));exit;
            }
            
            //验证默认仓库是否库存足够           
             $stock_data = $this->stock_model->get_depot_count($get_data[$k]['Fsku_id']);
             
            if($stock_data['count']<=0||$stock_data['count']-$send_count_json[$k]<0){
                echo json_encode(array("result"=>0,"msg"=>"库存不足以分配"));exit;
            }
            
            if($send_count_json[$k]>$get_data[$k]['Fallocate_count']-$get_data[$k]['Fsend_num']){
                echo json_encode(array("result"=>0,"msg"=>"出库数量不能大于报货数量"));exit;
            }
            
            $reback_data = array(
                "get_data"=>$get_data,
                "send_count_json"=>$send_count_json,
                "k"=>$k
            );
            //检查是否审核完成的退单且 可出库数量= skuid总数-sku退单数量 
            $this->get_order_refund($get_data[$k]['Forder_id'],$get_data[$k]['Fsku_id'],$send_count_json[$k],'0',$reback_data);
            
            //在报货表sell_allocate_item增加已配数量
            $this->db->set('Fsend_num','Fsend_num+'.$send_count_json[$k],false);
            $this->db->where('Fid',$get_data[$k]['Fsid']);
            $this->db->update('sell_allocate_item');
            
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
            unset($get_data[$k]['Fpos_name']);
            unset($get_data[$k]['Fclient_name']);
            unset($get_data[$k]['Fclient_phone']);
            unset($get_data[$k]['Forder_id']);
            $this->db->insert('odo_detail', $get_data[$k]);
            $id = $this->db->insert_id();
            
            //减可用库存 增加已配数量                公式   可用库存 = 总库存-已配数量
            $this->db->set('Fcount','Fcount-'.$get_data[$k]['Fsend_count'],false);
            $this->db->set('Fsend_count','Fsend_count+'.$get_data[$k]['Fsend_count'],false);
            $this->db->where('Fsku_id',$get_data[$k]['Fsku_id']);
            $this->db->update('stock_list');
            
            
//             //查找销售单号对应的id
//             $query = $this->db->get_where('sell_order',array("Forder_num"=>$get_data[$k]['Fsell_order']));
//             $order_data = $query->row_array();
            
//             //查找报货号对应的id
//             $query = $this->db->get_where('sell_allocate',array("Forder_num"=>$get_data[$k]['Fallocate_order']));
//             $allocate_data = $query->row_array();
            
            

            
            //先查出报货明细单的数量
            $query = $this->db->get_where('sell_allocate_item',array("Fid"=>$get_data[$k]['Fsid']));
            $allocate_item_data = $query->row_array();
            
            
            //更新报货单明细状态状态
            //完成配货
            if($allocate_item_data['num']==$allocate_item_data['send_num']){
                $this->update_allocate_status($get_data[$k]['Fsid'],'1');
            }
            //部分配货
            elseif($allocate_item_data['num']>$allocate_item_data['send_num']){
                $this->update_allocate_status($get_data[$k]['Fsid'],'2');
            }
            
            //检测并更新销售单状态
            $this->check_sell_order_status($get_data[$k]['Fsell_order']);
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
//             $query = $this->db->get_where('stock_list',array("Fsku_id"=>$odo_detail_data[$k]['sku_id']));
//             $stock_data = $query->row_array();
            
            //检查对应默认仓库库存
            $stock_data =  $this->stock_model->get_depot_count($odo_detail_data[$k]['sku_id']);
            
            $odo_detail_data[$k]['stock_count'] = $stock_data['count'];
     
            //查库位
            $query = $this->db->get_where('pos',array("Fid"=>$stock_data['pos_id']));
            $pos_data = $query->row_array();
            
            $odo_detail_data[$k]['pos_name'] = $pos_data['pos_name'];
            
            //查客户信息
            $query = $this->db->get_where('client',array("Fid"=>$odo_detail_data[$k]['client_id']));
            $client_data = $query->row_array();
            
            $odo_detail_data[$k]['client_name'] = $client_data['name'];
            $odo_detail_data[$k]['client_phone'] = $client_data['phone'];
            
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
    
    //自动审核减库存 并添加入库单和 入库详情
    public function auto_check(){
        
        //查出默认仓库的ID
        $moren_depot = $this->depot_model->get_moren_depot();
        
        $data['Fname'] = @$_SESSION['name'];
        $data['Fenter_depot'] = '1';
        $data['Fsource_depot'] = $moren_depot['id'];
        $data['Fodo_sn'] = "ODO".time();
        $data['Fbeizhu'] = '';
        $data['Fodo_date'] = date("Y-m-d",time());
        
        $data['Fcreate_time'] = date("Y-m-d H:i:s",time());
        $data['Fstatus'] = '4';
        
        //先查出所有没有完成配货的报货单
        $this->db->order_by('Fid', 'ASC');
        $query = $this->db->get_where('sell_allocate_item',array("Fstatus !="=>'1',"Fnum !="=>'0'));
        $allocate_data = $query->result_array();
        
        $total_count  = '';
        foreach($allocate_data as $k=>$v){            
                //检查对应默认仓库是否有库存               
               $stock_data =  $this->stock_model->get_depot_count($allocate_data[$k]['sku_id']); 
                if($stock_data['count']<=0){
                    continue;
                }
                
                //查出对应销售单的配货方式
                $this->db->select('Fallocate_mode,Forder_num');
                $query = $this->db->get_where('sell_order',array("Fid"=>$allocate_data[$k]['order_id']));
                $sell_order_data = $query->row_array();

                //需要配的数量
                $need_send_count = $allocate_data[$k]['num']-$allocate_data[$k]['send_num'];
                
                //0代表默认配货方式 有多少配多少
                if($sell_order_data['allocate_mode']==0){  
                    //如果库存小于需要配的数量则有多少配多少
                    if($stock_data['count']<$need_send_count){
                        $need_send_count = $stock_data['count'];
                    }
                }
                //1代表定制配货方式 直接跳过循环
                elseif($sell_order_data['allocate_mode']==1){
                    continue;
                }
                //2代表齐了配配货方式 
                elseif($sell_order_data['allocate_mode']==2){
                    //先查出该配货单所有sku的报货数量
                    $query = $this->db->get_where('sell_allocate_item',array("Fstatus !="=>'1',"Forder_id"=>$allocate_data[$k]['order_id'],"Fallocate_id"=>$allocate_data[$k]['allocate_id']));
                    $back_data = $query->result_array();
                            
                    //遍历找出该配货单所有的skuid是否库存满足
                    foreach ($back_data as $a=>$b){
                             $stock_data = $this->stock_model->get_depot_count($back_data[$a]['sku_id']); 
                             //任意一个sku不满足条件则直接结束外层循环
                             if($stock_data['count']<$back_data[$a]['num']-$back_data[$a]['send_num']){
                                 continue 2;
                             }
                    }                        
                }
                //3代表单款齐配配货方式
                elseif($sell_order_data['allocate_mode']==3){
                    //如果库存小于需要配的数量则不配
                    if($stock_data['count']<$need_send_count){     
                      continue;
                    }
                }
                
                //检查是否审核完成的退单且 可出库数量= skuid总数-sku退单数量
                $check_refund_status = $this->get_order_refund($allocate_data[$k]['order_id'],$allocate_data[$k]['sku_id'],$need_send_count,'1');
                if($check_refund_status){
                    $need_send_count = $check_refund_status;
                }
                
                //减可用库存 增加已配数量                公式   可用库存 = 总库存-已配数量
                $this->db->set('Fcount','Fcount-'.$need_send_count,false);
                $this->db->set('Fsend_count','Fsend_count+'.$need_send_count,false);
                $this->db->where('Fsku_id',$allocate_data[$k]['sku_id']);
                $this->db->update('stock_list');
                
                //在报货表sell_allocate_item增加已配数量
                $this->db->set('Fsend_num','Fsend_num+'.$need_send_count,false);
                $this->db->where('Fid',$allocate_data[$k]['id']);
                $this->db->update('sell_allocate_item');
                
                //查销售单号
                $query = $this->db->get_where('sell_order',array("Fid"=>$allocate_data[$k]['order_id']));
                $order_data = $query->row_array();

                //查报货单号
                $query = $this->db->get_where('sell_allocate',array("Fid"=>$allocate_data[$k]['allocate_id']));
                $get_allocate_data = $query->row_array();
                
                //查颜色 尺码
                $query = $this->db->get_where('sell_order_sku',array("Fid"=>$allocate_data[$k]['order_sku_id']));
                $sku_data = $query->row_array();
                

                
                $odo_detail_data['Fsid'] = $allocate_data[$k]['id'];
                $odo_detail_data['Fodo_sn'] = $data['Fodo_sn'];
                $odo_detail_data['Fsell_order'] = $order_data['order_num'];
                $odo_detail_data['Fallocate_order'] = $get_allocate_data['order_num'];
                $odo_detail_data['Fsku_id'] = $allocate_data[$k]['sku_id'];
                $odo_detail_data['Fspu'] = $allocate_data[$k]['spu_id'];
                $odo_detail_data['Fcolor'] = $sku_data['color'];
                $odo_detail_data['Fsize'] = $sku_data['size'];
                $odo_detail_data['Fallocate_count'] = $allocate_data[$k]['num'];
                $odo_detail_data['Fsend_count'] = $need_send_count;
                $odo_detail_data['Fclient_id'] = $order_data['client_id'];
                
                $odo_detail_data['Fshop_id'] = $order_data['shop_id'];
                
               //添加出库详情
                $this->db->insert('odo_detail', $odo_detail_data);
                $id = $this->db->insert_id();
                
                $total_count = $total_count+$need_send_count;
                
                //更新报货单明细状态和销售单状态
                 //完成配货
                if($allocate_data[$k]['num']==$allocate_data[$k]['send_num']+$need_send_count){
                    $this->update_allocate_status($allocate_data[$k]['id'],'1');
                }
                //部分配货
                elseif($allocate_data[$k]['num']>$allocate_data[$k]['send_num']+$need_send_count){
                    $this->update_allocate_status($allocate_data[$k]['id'],'2');
                }
                
                //检测并更新销售单状态
                $this->check_sell_order_status($sell_order_data['order_num']);
        }
        
        $data['Ftotal_count'] = $total_count;
        if($total_count<='0'){
            return false;
        }
        //添加出库单
        $this->db->insert('odo', $data);
        $id = $this->db->insert_id();
        return true;
    }
    
    //更新报货详情表状态
    public function update_allocate_status($allocate_id,$status){
        $this->db->set('Fstatus',$status);
        $this->db->where('Fid',$allocate_id);
        $this->db->update('sell_allocate_item');
        return true;
    }
    
    //查询该订单是否有审核的退单 有退单返回退单明细 没有返回false
    public function get_order_refund($order_id,$sku_id,$send_count,$type = false,$reback_data = false){
        
        $query = $this->db->get_where('sell_refund',array("Forder_id"=>$order_id,"Fstatus"=>'1'));
        $sell_refund_data = $query->result_array();
        
        if(!empty($sell_refund_data)){
            //skuid总退货数
            $sku_id_total_refund_count = '';
            foreach($sell_refund_data as $kk=>$vv){
                //先查出该销售单和skuid对应的退货单详情
                $query = $this->db->get_where('sell_refund_item',array("Forder_id"=>$sell_refund_data[$kk]['order_id'],"Fsku_id"=>$sku_id));
                $sell_refund_item_data = $query->row_array();                
                $sku_id_total_refund_count = $sku_id_total_refund_count+$sell_refund_item_data['num'];
            }

            //查出该销售单指定sku_id的所有报单详情 的已配货数量
            $query = $this->db->get_where('sell_allocate_item',array("Forder_id"=>$order_id,"Fsku_id"=>$sku_id));
            $sell_allocate_item_data = $query->result_array();
            
            //该订单sku_id总已配货数量
            $sku_id_total_send_count = '';
            foreach($sell_allocate_item_data as $a=>$b){
                $sku_id_total_send_count = $sku_id_total_send_count+$sell_allocate_item_data[$a]['send_num'];
            }
            
            //查出该销售单指定sku_id下单数量
            $query = $this->db->get_where('sell_order_sku',array("Forder_id"=>$order_id,"Fsku_id"=>$sku_id));
            $sell_order_num = $query->row_array();
            
            //可配数量 = skuid订单总理-退单总量-已配数量 
            $kepei_count = $sell_order_num['num']-$sku_id_total_refund_count-$sku_id_total_send_count;
            if($send_count>$kepei_count){
                if($type=='1'){
                    return $kepei_count;
                }
                else{
                    //进行 配货数回滚操作
                    foreach($reback_data['get_data'] as $aa=>$bb){
                        if($aa == $reback_data['k']){
                            break;
                        }
                        //在报货表sell_allocate_item减少已配数量
                        $this->db->set('Fsend_num','Fsend_num-'.$reback_data['send_count_json'][$aa],false);
                        $this->db->where('Fid',$reback_data['get_data'][$aa]['Fsid']);
                        $this->db->update('sell_allocate_item');
                    }

                    
                    echo json_encode(array("result"=>0,"msg"=>$sku_id."有".$sku_id_total_refund_count."件退货，出库数量不能多于下单数量-退货数量"));exit;
                }               
            }
            else{
                return $send_count;
                
            }
            
        }
        else{
           return false;
        }
    }
    

    //每次出库或修改出库单 检测销售单状态 (部分配货还是完成配货)然后更新销售单状态
    public function check_sell_order_status($order_num){
        //查询该订单的总数
        $query = $this->db->get_where('sell_order',array("Forder_num"=>$order_num));
        $sell_order_data = $query->row_array();
        
        //查询该订单所有报单出库详情
        $query = $this->db->get_where('sell_allocate_item',array("Forder_id"=>$sell_order_data['id']));
        $sell_allocate_item_data = $query->result_array();
        
        $total_num = '';
        
        foreach($sell_allocate_item_data as $k=>$v){
            $total_num = $total_num+$sell_allocate_item_data[$k]['send_num'];
        }


        if($sell_order_data['total_num']>$total_num&&$total_num!==0){
            $this->db->set('Fstatus','2');
            $this->db->where('Fid',$sell_order_data['id']);
            $this->db->update('sell_order');
        }
        elseif($sell_order_data['total_num']==$total_num){
            $this->db->set('Fstatus','1');
            $this->db->where('Fid',$sell_order_data['id']);
            $this->db->update('sell_order');
        }
        else{
            $this->db->set('Fstatus','0');
            $this->db->where('Fid',$sell_order_data['id']);
            $this->db->update('sell_order');
        }
        return true;
    }
    
    public function print_odo(){
        $odo_sn = @$_REQUEST['odo_sn'];
        
        //查出库单
        $query = $this->db->get_where('odo',array("Fodo_sn"=>$odo_sn));
        $odo_data = $query->row_array();
        
        //查仓库
        $query = $this->db->get_where('depot',array("Fid"=>$odo_data['source_depot']));
        $depot_data = $query->row_array();
        
        $odo_data['source_depot'] = $depot_data['depot_name'];
        
        $sql = "select distinct(Fshop_id) from t_odo_detail where Fodo_sn='{$odo_sn}'";
        $query = $this->db->query($sql);
        $shop_data = $query->result_array();


        $echo_odo_detail_data = array();
        
        foreach($shop_data as $k=>$v){//第一次遍历开始
            
            //查店铺名字
            $query = $this->db->get_where('shop',array("Fid"=>$shop_data[$k]['shop_id']));
            $get_shop_data = $query->row_array();
            
            $echo_odo_detail_data[$k]['shop_name'] = $get_shop_data['name'];
            
//             //找出该出库单按店铺来分组 所有的出库详情
//             $query = $this->db->get_where('odo_detail',array("Fodo_sn"=>$odo_sn,"Fshop_id"=>$shop_data[$k]['shop_id']));
//             $odo_detail_data = $query->result_array();
            
            $sql = "SELECT a.*,c.Fname,c.Fphone  FROM t_odo_detail a,t_sell_order b,t_client c where a.Fsell_order=b.Forder_num and b.Fclient_id=c.Fid and a.Fodo_sn='{$odo_sn}' and a.Fshop_id='{$shop_data[$k]['shop_id']}' order by c.Fphone ,a.Fsend_count desc";           
            $query = $this->db->query($sql);
            $check_odo_detail_page_data = $query->num_rows();
            
           //同一出库单 同一个店铺 总共多少页                       
            $total_page = ceil($check_odo_detail_page_data/13);
            
            $shop_send_count = '';
           
            //根据每一页去 获取出库单数据
            for($i=0;$i<$total_page;$i++){
                $page = $i*13;
                $sql = "SELECT a.*,c.Fname 'Fclient_name',c.Fphone 'Fclient_phone' FROM t_odo_detail a,t_sell_order b,t_client c where a.Fsell_order=b.Forder_num and b.Fclient_id=c.Fid and a.Fodo_sn='{$odo_sn}' and a.Fshop_id='{$shop_data[$k]['shop_id']}' order by c.Fphone ,a.Fsend_count desc limit {$page},13";
                $query = $this->db->query($sql);
                $odo_detail_data = $query->result_array();
                
                foreach($odo_detail_data as $a=>$b){//第二次遍历开始
                    //检查对应默认仓库库存
                    $stock_data =  $this->stock_model->get_depot_count($odo_detail_data[$k]['sku_id']);
                
                    //查库位
                    $query = $this->db->get_where('pos',array("Fid"=>$stock_data['pos_id']));
                    $pos_data = $query->row_array();
                    $odo_detail_data[$a]['pos_name'] = $pos_data['pos_name']?$pos_data['pos_name']:'0';
                     
                
                    //查出单张报货单明细的已配数量
                    $query = $this->db->get_where('sell_allocate_item',array("Fid"=>$odo_detail_data[$a]['sid']));
                    $total_send_num = $query->row_array();
                    $odo_detail_data[$a]['total_send_num'] = $total_send_num['send_num'];
                
                    $shop_send_count = $shop_send_count+$odo_detail_data[$a]['send_count'];
                
                    if($odo_data['status']!='1'){
                        //出库完成 减少该skuid总库存和已配数量   增加总出库数量
                        $this->db->set('Ftotal_count','Ftotal_count-'.$odo_detail_data[$a]['send_count'],false);
                        $this->db->set('Fsend_count','Fsend_count+'.$odo_detail_data[$a]['send_count'],false);
                        $this->db->set('Fout_count','Fout_count+'.$odo_detail_data[$a]['send_count'],false);
                        $this->db->where('Fsku_id',$odo_detail_data[$a]['sku_id']);
                        $this->db->where('Fdepot_id',$stock_data['depot_id']);
                        $this->db->update('stock_list');
                         
                    }
                 }//第二次遍历结束
                 
                 //每一页出库单的数据
                 $echo_odo_detail_data[$k]['odo_data'][$i]['detail_data'] = $odo_detail_data;
                 
                 //每一页客户包裹单的数据
                 //计算同一出库单 同一店铺 同一页 有几个客户
                 $sql = "SELECT a.Fclient_id FROM t_odo_detail a,t_sell_order b,t_client c where a.Fsell_order=b.Forder_num and b.Fclient_id=c.Fid and a.Fodo_sn='{$odo_sn}' and a.Fshop_id='{$shop_data[$k]['shop_id']}' order by c.Fphone ,a.Fsend_count desc limit {$page},13";
                 $query = $this->db->query($sql);
                 $client_data = $query->result_array();
                 
                 //去除重复值
                 $client_data = array_values($this->array_unique_fb($client_data));
                 //遍历客户  同一个出库单 同一个店铺 同一页 有几个客户就遍历几次
                 foreach($client_data as $aa=>$bb){ //第三次遍历开始
                 
                     $this->db->select('Fspu,Fsku_id,Fcolor,Fsize,Fsend_count');
                     $query = $this->db->get_where('odo_detail',array("Fodo_sn"=>$odo_sn,"Fclient_id"=>$client_data[$aa]['client_id']));
                     $client_detail_data = $query->result_array();
                 
                     //查客户信息
                     $query = $this->db->get_where('client',array("Fid"=>$client_data[$aa]['client_id']));
                     $get_client_data = $query->row_array();
                 
                     $echo_odo_detail_data[$k]['odo_data'][$i]['client_data'][$aa]['client_name'] = $get_client_data['name'];
                     $echo_odo_detail_data[$k]['odo_data'][$i]['client_data'][$aa]['print_time'] = date('Y-m-d H:i:s',time());
                 
                     $total_send_count = '';
                     //遍历客户详细数据
                     foreach($client_detail_data as $aaa=>$bbb){
                         $echo_odo_detail_data[$k]['odo_data'][$i]['client_data'][$aa]['data'][$aaa]['spu'] = $client_detail_data[$aaa]['spu'];
                         $echo_odo_detail_data[$k]['odo_data'][$i]['client_data'][$aa]['data'][$aaa]['color'] = $client_detail_data[$aaa]['color'];
                         $echo_odo_detail_data[$k]['odo_data'][$i]['client_data'][$aa]['data'][$aaa]['size'] = $client_detail_data[$aaa]['size'];
                         $echo_odo_detail_data[$k]['odo_data'][$i]['client_data'][$aa]['data'][$aaa]['send_count'] = $client_detail_data[$aaa]['send_count'];
                         $total_send_count = $total_send_count+$client_detail_data[$aaa]['send_count'];
                     }
                     $echo_odo_detail_data[$k]['odo_data'][$i]['client_data'][$aa]['total_send_count'] = $total_send_count;
                 
                     if($odo_data['status']!='1'){
                         $one = $i+1;
                         $two = $aa+1;
                         //生成包裹信息
                         $save_pack_data = array(
                             "Fodo_sn"=>$odo_sn,
                             "Fshop_id"=>$shop_data[$k]['shop_id'],
                             "Fclient_id"=>$client_data[$aa]['client_id'],
                             "Fuser_id"=>'',
                             "Fcount"=>$total_send_count,
                             "Fbeizhu"=>'',
                             "Ftype"=>$one.','.$two,
                             "Fpack_date"=>date("Y-m-d H:i:s",time()),
                             "Fdo_user_name"=>$_SESSION['name'],
                             "Fewm"=>$this->createNonceStr()
                         );
                         $this->db->insert('package', $save_pack_data);
                     }
                 }//第三次遍历结束
            }
            $echo_odo_detail_data[$k]['shop_send_count'] = $shop_send_count;
            $echo_odo_detail_data[$k]['total_page'] = $total_page;
            $echo_odo_detail_data[$k]['odo_sn_data'] = $odo_data;


            
        }//第一次遍历结束
        
        //最终出库单结果
        //print_r(json_encode($echo_odo_detail_data));die;

        //更新出库单状态
        $this->db->set('Fstatus','1');
        $this->db->where('Fodo_sn',$odo_sn);
        $this->db->update('odo');
        
        return $echo_odo_detail_data;
    }
    
  public function get_print(){
      $query = $this->db->get_where('print',array("Fid"=>'1'));
      $print_data = $query->row_array();
      return $print_data;
  }
  
  public function change_print(){
    $type = @$_REQUEST['type'];
    $value = @$_REQUEST['value'];
    
    $this->db->set($type,$value);
    $this->db->where('Fid','1');
    $this->db->update('print');
    return true;
  }
  
  //获取包裹列表
  public function get_package_list($page_number = 1){
      $page_count = ($page_number-1)*15;
  
      $this->db->order_by('Fid', 'DESC');
      $query = $this->db->get('package','15',$page_count);
      $data = $query->result_array();
  
      foreach($data as $k=>$v){
  
            //查员工信息
            $query = $this->db->get_where('user',array("Fuid"=>$data[$k]['user_id']));
            $user_data = $query->row_array();
            
            //查客户信息
            $query = $this->db->get_where('client',array("Fid"=>$data[$k]['client_id']));
            $client_data = $query->row_array();
  
            //查店铺名字
            $query = $this->db->get_where('shop',array("Fid"=>$data[$k]['shop_id']));
            $get_shop_data = $query->row_array();
            
            $data[$k]['user_id'] = $user_data['name'];            
            $data[$k]['client_name'] = $client_data['name'];
            $data[$k]['client_phone'] = $client_data['phone'];
    
            $data[$k]['shop_id'] = $get_shop_data['name'];
      }
  
      return $data ;
  }
  
  //查询条件获取 报货单列表数据
  public function get_all_offer_where_list(){
      $page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
      $page_count = ($page-1)*15;
  
      $search_data  = array(
          "search"=>@$_REQUEST['search'],
          "start_date"=>@$_REQUEST['start_date'],
          "end_date"=>@$_REQUEST['end_date'],
          "allocate_mode"=>@$_REQUEST['allocate_mode']
      );


      $wherelist = array();
      if(!empty($search_data['search'])){
          $wherelist[] = "(b.Forder_num like '%{$search_data['search']}%' or e.Fname like '%{$search_data['search']}%' or e.Fphone like '%{$search_data['search']}%' or f.Fname like '%{$search_data['search']}%')";
  
          if(!empty($search_data['start_date'])){
              $wherelist[] = "b.Fcreate_at >=UNIX_TIMESTAMP('{$search_data['start_date']}')";
          }
          if(!empty($search_data['end_date'])){
              $wherelist[] = "b.Fcreate_at <=UNIX_TIMESTAMP('{$search_data['end_date']}')";
          }
          if($search_data['allocate_mode']!==''){
              $wherelist[] = "b.Fallocate_mode = '{$search_data['allocate_mode']}'";
          }
          //组装查询条件
          if(count($wherelist) > 0){
              $where = implode(' AND ' , $wherelist);
          }
          //判断查询条件
          $where = isset($where) ? $where : '';
  
          $get_count_sql = "select a.*,b.*,c.Forder_num 'Fallocate_num',c.Fcreate_at 'Fallocate_create_at',d.Fcolor,d.Fsize,e.Fname 'Fclient_name',e.Fphone 'Fclient_phone',f.Fname 'Fuser_name' from t_sell_allocate_item a,t_sell_order b,t_sell_allocate c,t_sell_order_sku d,t_client e,t_user f where a.Forder_id = b.Fid and a.Fallocate_id=c.Fid  and a.Forder_sku_id= d.Fid and b.Fclient_id = e.Fid  and b.Fuser_id=f.Fuid and {$where} ";
  
          $sql = $get_count_sql."order by a.Fid desc limit {$page_count},15";
      }
      else{
          if(!empty($search_data['start_date'])){
              $wherelist[] = "b.Fcreate_at >=UNIX_TIMESTAMP('{$search_data['start_date']}')";
          }
          if(!empty($search_data['end_date'])){
              $wherelist[] = "b.Fcreate_at <=UNIX_TIMESTAMP('{$search_data['end_date']}')";
          }
          if($search_data['allocate_mode']!==''){
              $wherelist[] = "b.Fallocate_mode = '{$search_data['allocate_mode']}'";
          }
          //组装查询条件
          if(count($wherelist) > 0){
              $where = "AND ".implode(' AND ' , $wherelist);
          }
          //判断查询条件
          $where = isset($where) ? $where : '';
  
          $get_count_sql = "select a.*,b.*,c.Forder_num 'Fallocate_num',c.Fcreate_at 'Fallocate_create_at',d.Fcolor,d.Fsize,e.Fname 'Fclient_name',e.Fphone 'Fclient_phone',f.Fname 'Fuser_name' from t_sell_allocate_item a,t_sell_order b,t_sell_allocate c,t_sell_order_sku d,t_client e,t_user f where a.Forder_id = b.Fid and a.Fallocate_id=c.Fid  and a.Forder_sku_id= d.Fid and b.Fclient_id = e.Fid  and b.Fuser_id=f.Fuid {$where} ";  
          
          $sql = $get_count_sql."order by a.Fid desc limit {$page_count},15";
          
      }
  
  
      //读取报货单
      $check_offer_data = $this->index_model->get_query($sql);
  
      foreach($check_offer_data as $k=>$v){
            //收货方式
            $check_offer_data[$k]['delivery_type'] = $this->get_delivery_type($check_offer_data[$k]['delivery_type']);

            
            //付款方式
            $check_offer_data[$k]['payment'] = $this->get_payment($check_offer_data[$k]['payment']);
           
            //配货方式
            $check_offer_data[$k]['allocate_mode'] = $this->get_allocate_mode($check_offer_data[$k]['allocate_mode']);
            
            //查出默认仓库的库存
            $stock_data = $this->stock_model->get_depot_count($check_offer_data[$k]['sku_id']);
            
            $check_offer_data[$k]['stock_count'] = $stock_data['count'];
            if(!$check_offer_data[$k]['stock_count']){
                $check_offer_data[$k]['stock_count'] = '0';
            }
            
            //已配
            $check_offer_data[$k]['send_count'] = $stock_data['send_count'];
            
            if(!$check_offer_data[$k]['send_count']){
                $check_offer_data[$k]['send_count'] = '0';
            }
            //查库位
            $query = $this->db->get_where('pos',array("Fid"=>$stock_data['pos_id']));
            $pos_data = $query->row_array();
            
            $check_offer_data[$k]['pos_name'] = $pos_data['pos_name'];
            
            //查询该sku_id未配数量
            $query = $this->db->get_where('sell_allocate_item',array("Fsku_id"=>$check_offer_data[$k]['sku_id'],"Fstatus !="=>'1'));
            $allocate_sku_id_data = $query->result_array();
            
            $weipei_count = '';
            foreach($allocate_sku_id_data as $a=>$b){
                $weipei_count = $weipei_count+$allocate_sku_id_data[$a]['num']-$allocate_sku_id_data[$a]['send_num'];
            }
            $check_offer_data[$k]['weipei_count'] = $weipei_count;
            
            
            $check_offer_data[$k]['spu'] = $check_offer_data[$k]['spu_id'];
      }
      
      $back_data = array(
          "offer_data"=>$check_offer_data,
          "get_count_sql"=>$get_count_sql,
          'search_data'=>$search_data
      );
      
      return $back_data;
  }
  
  //查询条件获取 出库单列表数据
  public function get_all_odo_where_list(){
      $page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
      $page_count = ($page-1)*15;
  
      $search_data  = array(
          "search"=>@$_REQUEST['search'],
          "odo_date"=>@$_REQUEST['odo_date'],
          "status"=>@$_REQUEST['status'],        
      );
  
  
      $wherelist = array();
      if(!empty($search_data['search'])){
          $wherelist[] = "(a.Fodo_sn like '%{$search_data['search']}%' or b.Fsku_id like '%{$search_data['search']}%')";
  
          if(!empty($search_data['odo_date'])){
              $wherelist[] = "a.Fodo_date = '{$search_data['odo_date']}'";
          }
          if($search_data['status']!==''){
              $wherelist[] = "a.Fstatus = '{$search_data['status']}'";
          }
          //组装查询条件
          if(count($wherelist) > 0){
              $where = implode(' AND ' , $wherelist);
          }
          //判断查询条件
          $where = isset($where) ? $where : '';
  
          $get_count_sql = "select a.* from t_odo a,t_odo_detail b where a.Fodo_sn = b.Fodo_sn  and {$where} ";
  
          $sql = $get_count_sql."order by a.Fid desc limit {$page_count},15";
      }
      else{
          if(!empty($search_data['odo_date'])){
              $wherelist[] = "Fodo_date = '{$search_data['odo_date']}'";
          }
          if($search_data['status']!==''){
              $wherelist[] = "Fstatus = '{$search_data['status']}'";
          }
          //组装查询条件
          if(count($wherelist) > 0){
              $where = "where ".implode(' AND ' , $wherelist);
          }
          //判断查询条件
          $where = isset($where) ? $where : '';
  
          $get_count_sql = "select * from t_odo {$where} ";
          
          $sql = $get_count_sql."order by Fid desc limit {$page_count},15";
  
      }

  
      //读取报货单
      $check_odo_data = $this->index_model->get_query($sql);
  
  
      $back_data = array(
          "odo_data"=>$check_odo_data,
          "get_count_sql"=>$get_count_sql,
          'search_data'=>$search_data
      );
  
      return $back_data;
  }
  
  //获取退货单列表
  public function get_refund_list($page_number = 1){
      $page_count = ($page_number-1)*15;
  
      $this->db->order_by('Fid', 'DESC');
      $query = $this->db->get('sell_refund','15',$page_count);
      $data = $query->result_array();
  
      foreach($data as $k=>$v){
  
          //查销售单信息
          $query = $this->db->get_where('sell_order',array("Fid"=>$data[$k]['order_id']));
          $sell_data = $query->row_array();
  
          $data[$k]['sell_order'] = $sell_data['order_num'];
  
          $data[$k]['create_at'] = date("Y-m-d H:i:s",$data[$k]['create_at']);
  
          //查客户信息
          $query = $this->db->get_where('client',array("Fid"=>$sell_data['client_id']));
          $client_data = $query->row_array();
  
          //查用户信息
          $query = $this->db->get_where('user',array("Fuid"=>$data[$k]['create_user_id']));
          $user_data = $query->row_array();
  
          $data[$k]['client_name'] = $client_data['name'];
          $data[$k]['client_phone'] = $client_data['phone'];
          $data[$k]['user_name'] = $user_data['name'];
  
          if($data[$k]['status']=='1'){
              $data[$k]['status'] = '审核完成';
          }
          else{
              $data[$k]['status'] = '未审核';
          }
      }
  
      return $data ;
  }
  
  //查询条件获取 退货单列表数据
  public function get_refund_where_list(){
      $page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
      $page_count = ($page-1)*15;
  
      $search_data  = array(
          "search"=>@$_REQUEST['search'],
          "status"=>@$_REQUEST['status'],
      );
  
  
      $wherelist = array();
      if(!empty($search_data['search'])){
          $wherelist[] = "(b.Forder_num like '%{$search_data['search']}%' or d.Fname like '%{$search_data['search']}%' or d.Fphone like '%{$search_data['search']}%' or c.Fname like '%{$search_data['search']}%' )";
  
          if($search_data['status']!==''){
              $wherelist[] = "a.Fstatus = '{$search_data['status']}'";
          }
          //组装查询条件
          if(count($wherelist) > 0){
              $where = implode(' AND ' , $wherelist);
          }
          //判断查询条件
          $where = isset($where) ? $where : '';
  
          $get_count_sql = "select a.*,b.Forder_num 'Fsell_order',d.Fname 'Fclient_name',d.Fphone 'Fclient_phone',c.Fname 'Fuser_name' from t_sell_refund a,t_sell_order b,t_user c,t_client d where a.Forder_id = b.Fid and a.Fcreate_user_id = c.Fuid and b.Fclient_id=d.Fid and {$where} ";
  
          $sql = $get_count_sql."order by a.Fid desc limit {$page_count},15";
      }
      else{
  
          if($search_data['status']!==''){
              $where = "and a.Fstatus = '{$search_data['status']}'";
          }

          //判断查询条件
          $where = isset($where) ? $where : '';
          $get_count_sql = "select a.*,b.Forder_num 'Fsell_order',d.Fname 'Fclient_name',d.Fphone 'Fclient_phone',c.Fname 'Fuser_name'from t_sell_refund a,t_sell_order b,t_user c,t_client d where a.Forder_id = b.Fid and a.Fcreate_user_id = c.Fuid and b.Fclient_id=d.Fid {$where} ";
          $sql = $get_count_sql."order by Fid desc limit {$page_count},15";
  
      }
      //读取报货单
      $check_refund_data = $this->index_model->get_query($sql);
  
  
      $back_data = array(
          "refund_data"=>$check_refund_data,
          "get_count_sql"=>$get_count_sql,
          'search_data'=>$search_data
      );
  
      return $back_data;
  }
  
  public function get_payment($payment){
      switch($payment){
          case"0":
              $payment = '默认付款方式';
          break;
      
          case"1":
              $payment = '现金';
          break;
      
          case"2":
              $payment = '银行汇款';
          break;
      
          case"3":
              $payment = 'POS机刷卡';
          break;
      
          case"4":
              $payment = '其他';
          break;
      
          case"5":
              $payment = '微信';
          break;
      
          case"6":
              $payment = '支付宝';
          break;
      
          case"7":
              $payment = '未付';
          break;
      }
      
      return $payment;
  }
  
  public function get_delivery_type($delivery_type){
      switch($delivery_type){
          case"1":
              $delivery_type = '仓库自提';
          break;
      
          case"2":
              $delivery_type = '档口自提';
          break;
      
          case"3":
              $delivery_type = '物流配送';
          break;
      }
      return $delivery_type;
  }
  public function get_allocate_mode($allocate_mode){
      switch ($allocate_mode){
          case"0":
              $allocate_mode = '默认';
          break;
      
          case"1":
              $allocate_mode = '定制';
          break;
      
          case"2":
              $allocate_mode = '齐了配';
          break;
      
          case"3":
              $allocate_mode = '单款齐配';
          break;
      }
      return $allocate_mode;
  }
  
  
  
  public function createNonceStr($length = 20) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
  }

    public function array_unique_fb($array2D)
    {
        foreach ($array2D as $k=>$v)
        {
            $v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[$k] = $v;
        }
        $temp = array_unique($temp);    //去掉重复的字符串,也就是重复的一维数组
        foreach ($temp as $k => $v)
        {
            $array=explode(",",$v);        //再将拆开的数组重新组装
            $temp2[$k]["client_id"] =$array[0];   
        }
        return $temp2;
    }
}
