<?php

class Storage_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('depot/stock_model');
        $this->load->model('depot/index_model');
        $this->load->model('depot/depot_model');
    }
  
    public  function add_storage_sku(){
    	$data['Fstorage_sn'] = $storage_sn = @$_REQUEST['storage_sn'];
    	$data['Fsku_id'] = $sku_id = @$_REQUEST['sku_id'];
    	$data['Fcount'] = $count = @$_REQUEST['count'];
    	$data['Fenter_depot'] = $enter_depot = @$_REQUEST['depot_id'];
    	
    	$data['Fstorage_date'] = $storage_date = @$_REQUEST['storage_date'];
    	
    	$storage_type = @$_REQUEST['storage_type'];
    	$source_depot = @$_REQUEST['source_depot'];
    	
    	//状态表示是否详情页进来添加的
    	$status = @$_REQUEST['status'];

    	
    	$sql = "select a.Fgoods_id,a.Fsku_id,b.Fname,c.Fsize_info from t_sku a,t_color b,t_size c where a.Fcolor_id = b.Fid and a.Fsize_id=c.Fid and a.Fsku_id='{$sku_id}'";
    	$query = $this->db->query($sql);
    	$back_data = $query->row_array();

    	$data['Fcolor'] = $back_data['name'];
    	$data['Fsize'] = $back_data['size_info'];
    	
    	$data['Fgoods_id'] = $back_data['goods_id'];
    	$data['Faddtime'] = date("Y-m-d H:i:s",time());
    	$data['Fstock_status'] = '2';
    	$data['Ftotal_count'] = $count;
    	
    	if($status){
    	    $data['Fstorage_date'] = @$_REQUEST['storage_date'];
    	    $data['Fstatus'] = '1';
    	    
    	    $this->db->set('Fstatus', '2');
    	    $this->db->where("Fstorage_sn",$storage_sn);
    	    $this->db->update('storage_list');
    	}
    	
    	$check_data = $this->check_storage_sku($storage_sn,$sku_id,$enter_depot);
    	

    	
    	if($check_data){
    	    if($check_data['stock_status']==1){
    	        $this->db->set('Ftotal_count', $count, FALSE);
    	    }
    	    else{
    	        $this->db->set('Ftotal_count', 'Ftotal_count+'.$count, FALSE);
    	    }
    		$this->db->set('Fcount', 'Fcount+'.$count, FALSE);
    		$this->db->set('Ftotal_count', 'Ftotal_count+'.$count, FALSE);
    		$this->db->set('Faddtime',$this->addtime);
    		$this->db->set('Fstock_status','2');
    		$this->db->where("Fsku_id",$sku_id);
    		$this->db->where("Fstorage_sn",$storage_sn);
    		$this->db->where("Fenter_depot",$enter_depot);
    		$this->db->update('storage_list_detail');   
    		
    		if($storage_type==3){
    		    
        		$this->db->set('Fcount', 'Fcount-'.$count, FALSE);
        		$this->db->set('Ftotal_count', 'Ftotal_count-'.$count, FALSE);
        		$this->db->set('Faddtime',$this->addtime);
        		$this->db->set('Fstock_status','2');
        		$this->db->where("Fsku_id",$sku_id);
        		$this->db->where("Fstorage_sn",$storage_sn);
        		$this->db->where("Fenter_depot",$source_depot);
        		$this->db->update('storage_list_detail'); 
    		}
    		
    	}
    	else{
    		$this->db->insert('storage_list_detail', $data);
    		$id = $this->db->insert_id(); 
    		if($storage_type==3){
    		    $data['Ftotal_count'] = $count-$count-$count;
    		    $data['Fcount'] = $count-$count-$count;
    		    $data['Fenter_depot'] = $source_depot;
    		    $this->db->insert('storage_list_detail', $data);
    		    $id = $this->db->insert_id();
    		}

    	}

    	//如果状态存在表示从详情页进来添加的
    	if($status){

    	    //给入库单加数量
    	    $this->db->set('Fcount', "Fcount+".$count, FALSE);
    	    $this->db->where("Fstorage_sn",$storage_sn);
    	    $this->db->update('storage_list');
    	}   	
    	
    	$get_data = $this->get_all_storage_sku($storage_sn,$enter_depot);
    	
    	return $get_data; 
    }
    
    //删除某张入库单某条sku
    
    public function delete_storage_sku(){
        $storage_sn = $_REQUEST['storage_sn'];
        $sku_id = $_REQUEST['sku_id'];
        $before_count = @$_REQUEST['before_count'];
        $enter_depot = @$_REQUEST['enter_depot'];
        //查出修改前数量多少
        $check_before = $this->check_storage_sku($storage_sn,$sku_id,$enter_depot);
        
        //从入库详情删除对应skuid
        $this->db->where('Fstorage_sn',$storage_sn);
        $this->db->where('Fsku_id',$sku_id);
        $this->db->delete('storage_list_detail');
        
        
        //从入库单剔除数量
        $this->db->set('Fcount','Fcount-'.$before_count,false);
        $this->db->set('Fstatus','2');
        $this->db->where('Fstorage_sn',$storage_sn);
        $this->db->update('storage_list');
        

        
        if($check_before['stock_status']=='1'){
            
            //从库存表删除对应skuid数量 
            $this->db->set('Fhis_count','Fhis_count-'.$before_count,false);
            $this->db->set('Ftotal_count','Ftotal_count-'.$before_count,false);
            $this->db->set('Fcount','Fcount-'.$before_count,false);
            $this->db->where('Fsku_id',$sku_id);
            $this->db->update('stock_list');
        } 
        return true;
    }
    
    //改变库存
    public function change_storage_sku(){
    	$storage_sn = @$_REQUEST['storage_sn'];
    	$sku_id = @$_REQUEST['sku_id'];
    	$count = @$_REQUEST['count'];
    	$storage_type = @$_REQUEST['storage_type'];
    	$enter_depot = @$_REQUEST['enter_depot'];
    	$source_depot = @$_REQUEST['source_depot'];
    	
    	$status = @$_REQUEST['status'];
    	
        //如果状态存在表示从详情页进来添加的
    	if($status){
    	   
    	 //查出修改前数量多少
    	 $before = $this->check_storage_sku($storage_sn,$sku_id,$enter_depot);
    	 
    	 $now_count = $count-$before['count'];
    	 
    	    //给入库单改变数量
    	    $this->db->set('Fcount', "Fcount+".$now_count, FALSE);
    	    $this->db->set('Fstatus', "2");
    	    $this->db->where("Fstorage_sn",$storage_sn);
    	    $this->db->update('storage_list');
    	} 
    	

    	if($storage_type=='3'){
//     	    if($before['stock_status']==1){
//     	        $this->db->set('Ftotal_count', $now_count);
//     	    }
//     	    else{
//     	        $this->db->set('Ftotal_count', $count);
//     	    }
            //审核完成暂时不允许修改入库单了
    	    $this->db->set('Ftotal_count', $count-$count-$count);
    	    $this->db->set('Fcount', $count-$count-$count);
    	    $this->db->set('Fstock_status', '2');
    	    $this->db->set('Faddtime',$this->addtime);
    	    $this->db->where("Fsku_id",$sku_id);
    	    $this->db->where("Fstorage_sn",$storage_sn);
    	    $this->db->where("Fenter_depot",$source_depot);
    	    $data = $this->db->update('storage_list_detail');
    	}
    	
    	if($before['stock_status']==1){
    	    $this->db->set('Ftotal_count', $now_count);
    	}
    	else{
    	    $this->db->set('Ftotal_count', $count);
    	}
    	$this->db->set('Fcount', $count);
    	$this->db->set('Fstock_status', '2');
    	$this->db->set('Faddtime',$this->addtime);
    	$this->db->where("Fsku_id",$sku_id);
    	$this->db->where("Fstorage_sn",$storage_sn);
    	$this->db->where("Fenter_depot",$enter_depot);
    	$data = $this->db->update('storage_list_detail');
    	return $data;
    }
    
    public function change_storage_sku_beizhu(){
    	$storage_sn = @$_REQUEST['storage_sn'];
    	$sku_id = @$_REQUEST['sku_id'];
    	$beizhu = @$_REQUEST['beizhu'];
    	 
    	$this->db->set('Fbeizhu', $beizhu);
    	$this->db->set('Faddtime',$this->addtime);
    	$this->db->where("Fsku_id",$sku_id);
    	$this->db->where("Fstorage_sn",$storage_sn);
    	$data = $this->db->update('storage_list_detail');
    	return $data;
    }
    
    public function change_storage_beizhu(){
        $storage_sn = @$_REQUEST['storage_sn'];
        $beizhu = @$_REQUEST['beizhu'];
    
        $this->db->set('Fbeizhu', $beizhu);
        $this->db->where("Fstorage_sn",$storage_sn);
        $data = $this->db->update('storage_list');
        return $data;
    }
    
    //从该storage_list_detail表中取出该订单指定skuid的所有数据
    public function check_storage_sku($storage_sn,$sku_id,$depot_id){
    	$query = $this->db->get_where('storage_list_detail',array("Fsku_id"=>$sku_id,"Fstorage_sn"=>$storage_sn,"Fenter_depot"=>$depot_id));
    	$data = $query->row_array();
    	return $data ;
    }
    
    //从该storage_list_detail表中取出该订单所有数据
    public function check_storage_sn($storage_sn,$page = false,$type = false){

        if($page){
            $this->db->order_by('Faddtime', "DESC");
            $query = $this->db->get_where('storage_list_detail',array("Fstorage_sn"=>$storage_sn),'10','0');
            $data = $query->result_array();
            return $data ;
        }
        else{
            if($type){
                $this->db->order_by('Faddtime', "DESC");
                $query = $this->db->get_where('storage_list_detail',array("Fstorage_sn"=>$storage_sn));
                $data = $query->result_array();
                return $data ;
            }
            else{
                $this->db->order_by('Faddtime', "DESC");
                $query = $this->db->get_where('storage_list_detail',array("Fstorage_sn"=>$storage_sn,"Fstock_status"=>'2'));
                $data = $query->result_array();
                return $data ;
            }

        }
    }
    public function check_depot_storage_list_detail($storage_sn,$depot_id,$type = false){
        if($type){
            $this->db->order_by('Faddtime', "DESC");
            $query = $this->db->get_where('storage_list_detail',array("Fstorage_sn"=>$storage_sn,"Fenter_depot"=>$depot_id),'10',0);
            $data = $query->result_array();
            return $data ;
        }
       else{
           $this->db->order_by('Faddtime', "DESC");
           $query = $this->db->get_where('storage_list_detail',array("Fstorage_sn"=>$storage_sn,"Fstock_status"=>'2',"Fenter_depot"=>$depot_id));
           $data = $query->result_array();
           return $data ;
       }
    }
    public  function get_all_storage_sku($storage_sn,$enter_depot){

    	$page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
    	$page_count = ($page-1)*10;
    	
    	

    	$sql1 = "select * from t_storage_list_detail where Fstorage_sn='{$storage_sn}' and Fenter_depot = '{$enter_depot}'order by Faddtime DESC limit {$page_count},10";
    	$query = $this->db->query($sql1);
    	$data = $query->result_array();

    	return $data ;
    }

    
    //检测订单是否重复提交
    public function check_storage_list($storage_sn){

        $data = $this->get_sn_storage($storage_sn);
        if(!empty($data)){
            //查仓库
            $depot_name = $this->depot_model->get_depot($data['enter_depot']);
            $source_depot_name = $this->depot_model->get_depot($data['source_depot']);
            
            $data['source_depot_id'] = $data['source_depot'];
            $data['storage_type_id'] = $data['storage_type'];
            $data['depot_id'] = $data['enter_depot'];
            $data['enter_depot'] = $depot_name['depot_name'];
            $data['source_depot'] = $source_depot_name['depot_name'];
            
            switch($data['storage_type']){
                case"1":
                    $data['storage_type'] = '供应商采购';
                    //查出供应商
                    $supplier = $this->depot_model->get_supplier($data['supplier']);
                    $data['supplier'] = $supplier['supplier_name'];
                    
                    break;
            
                case"2":
                    $data['storage_type'] = '销售退货';
                    break;
            
                case"3":
                    $data['storage_type'] = '调拨入库';
                    break;
            
                case"4":
                    $data['storage_type'] = '其他入库';
                    break;
            }
        }
        else{
            return false;
        }
        return $data ;
    }
    
    //添加入库
    
    public function add_storage(){
        $check_storage_list =  $this->check_storage_list(@$_REQUEST['storage_sn']);
        if($check_storage_list){
            echo json_encode(array("result"=>0,"msg"=>"请勿重复提交数据"));exit;
        }
        
    	$save_data = array();
    	$save_data['Fstorage_sn'] = $storage_sn = @$_REQUEST['storage_sn'];
    	$save_data['Fsn'] = $sn = @$_REQUEST['sn'];
    	$save_data['Fstorage_date'] = $storage_date = @$_REQUEST['storage_date'];
    	$save_data['Fstorage_type'] = $storage_type = @$_REQUEST['storage_type'];
    	$save_data['Fenter_depot'] = $enter_depot = @$_REQUEST['enter_depot'];
    	$save_data['Fname'] = $name = @$_REQUEST['name'];
    	$save_data['Fsupplier'] = $supplier = @$_REQUEST['supplier'];
    	$save_data['Fsource_depot'] = $source_depot = @$_REQUEST['source_depot'];
    	$save_data['Fbeizhu'] = $beizhu = @$_REQUEST['beizhu'];
    	$save_data['Fupload_images'] = $upload_images = @$_REQUEST['upload_images'];
    	$save_data['Faddtime'] = $this->addtime;
    	$save_data['Fstatus'] = '2';
    	
    	//从该storage_list_detail表中取出该订单所有数据
    	$sku_data =  $this->check_depot_storage_list_detail($storage_sn,$enter_depot,'');
    	if(empty($sku_data)){
    	    echo json_encode(array("result"=>0,"msg"=>"请提交入库数据"));exit;
    	}
    	
    	//库存表添加数据
    	$add_count = '';
    	foreach($sku_data as $k=>$v){
    	    
    	    
    	      //如果为调拨入库 验证对应仓库的sku的总库存是否满足（此处还需要修改是总库存还是可用库存）
    	      
    	    
    	      if($storage_type=='3'){
    	          $check_stock_sku_id = $this->stock_model->check_stock_sku_id($sku_data[$k]['sku_id'],$source_depot);
    	          if($check_stock_sku_id['count']<$sku_data[$k]['count']){
    	              echo json_encode(array("result"=>0,"msg"=>"出货仓库货源不足(".$sku_data[$k]['goods_id'].",".$sku_data[$k]['color'].",".$sku_data[$k]['size']."),剩余:".$check_stock_sku_id['count']));exit;
    	          }
    	      }    	      
    	    $add_count = $add_count+$sku_data[$k]['count'];
    	}
    	
    	//如果入库类型为调拨入库
    	if($storage_type==3){
    	    if($enter_depot==$source_depot){
    	        echo json_encode(array("result"=>0,"msg"=>"进货仓库和出货仓库不能一样！"));exit;
    	    }
    	    //验证出货仓库对应的sku是否有足够库存
    	}
    	$this->db->insert('storage_list', $save_data);
    	$id = $this->db->insert_id();
    	
    	//改变入库sku详情
    	$this->db->set('Fstatus', '1');
    	$this->db->set('Fstock_status', '2');
    	$this->db->set('Fstorage_date',$storage_date);
    	$this->db->where("Fstorage_sn",$storage_sn);
    	$this->db->update('storage_list_detail');
    	
    	//清除状态不为1的数据
    	$this->db->where('Fstatus !=', '1');
    	$this->db->delete('storage_list_detail');
    	


    	   //单张入库单入库总数量
    	   $this->db->set('Fcount', $add_count);
    	   $this->db->where("Fstorage_sn",$storage_sn);
    	   $this->db->update('storage_list');
    	return $id;
    }
    
    //审核往库存表加库存和减库存
    public function check_add_sku(){
        
        $storage_sn = @$_REQUEST['storage_sn'];
        $check_sn = $this->get_sn_storage($storage_sn);
        //状态为1表示已经审核
        if($check_sn['status']==1){
            echo json_encode(array("result"=>0,"msg"=>"请勿重复审核！"));exit;
        }
        $sku_data = $this->check_storage_sn($storage_sn);
        
        if($check_sn['storage_type']=='3'){
            
            foreach($sku_data as $k1=>$v1){
                //判断如果是调拨入库 验证出货仓库库存是否满足            
                $check_stock_sku_id = $this->stock_model->check_stock_sku_id($sku_data[$k1]['sku_id'],$check_sn['source_depot']);
                if($check_stock_sku_id['count']<$sku_data[$k1]['count']){
                    echo json_encode(array("result"=>0,"msg"=>"出货仓库货源不足(".$sku_data[$k1]['goods_id'].",".$sku_data[$k1]['color'].",".$sku_data[$k1]['size']."),剩余:".$check_stock_sku_id['count']));exit;
                }            
            }
        }

        foreach($sku_data as $k=>$v){
            //判断skuid是否已经存在于库存表
            $check_stock_list_sku_id = $this->stock_model->check_stock_sku_id($sku_data[$k]['sku_id'],$sku_data[$k]['enter_depot']);
            
            if(empty($check_stock_list_sku_id)){
                $stock_data = array(
                    "Fdepot_id"=>$sku_data[$k]['enter_depot'],
                    "Fgoods_id"=>$sku_data[$k]['goods_id'],
                    "Fsku_id"=>$sku_data[$k]['sku_id'],
                    "Fcolor"=>$sku_data[$k]['color'],
                    "Fsize"=>$sku_data[$k]['size'],
                    "Fcount"=>$sku_data[$k]['total_count'],
                    "Ftotal_count"=>$sku_data[$k]['total_count'],
                    "Fhis_count"=>$sku_data[$k]['total_count']
                );
                $this->db->insert('stock_list', $stock_data);
            }
            else{
                $this->db->set('Fcount', 'Fcount+'.$sku_data[$k]['total_count'], FALSE);
                $this->db->set('Ftotal_count', 'Ftotal_count+'.$sku_data[$k]['total_count'], FALSE);
                $this->db->set('Fhis_count', 'Fhis_count+'.$sku_data[$k]['total_count'], FALSE);
                $this->db->where("Fsku_id",$sku_data[$k]['sku_id']);
                $this->db->where("Fdepot_id",$sku_data[$k]['enter_depot']);
                $this->db->update('stock_list');
            }
            //添加完成再把该条数据状态改为1
            $this->db->set('Fstock_status','1');
            $this->db->set('Faddtime',$this->addtime);
            $this->db->where("Fid",$sku_data[$k]['id']);
            $this->db->update('storage_list_detail');
            
          //减出货仓库的sku库存！
          if($check_sn['storage_type']=='3'){
              $this->db->set('Fcount', 'Fcount-'.$sku_data[$k]['total_count'], FALSE);
              $this->db->set('Ftotal_count', 'Ftotal_count-'.$sku_data[$k]['total_count'], FALSE);
              $this->db->set('Fhis_count', 'Fhis_count-'.$sku_data[$k]['total_count'], FALSE);
              $this->db->where("Fsku_id",$sku_data[$k]['sku_id']);
              $this->db->where("Fdepot_id",$check_sn['source_depot']);
              $this->db->update('stock_list');
           }

        }
        
        //审核完成再把该条数据状态改为1
        $this->db->set('Fstatus','1');
        $this->db->where("Fstorage_sn",$storage_sn);
        $this->db->update('storage_list');
        
        return true;
    }
    
    //获取全部入库列表数据   
    public function get_all_storage_list($page_number = 1){
        $page_count = ($page_number-1)*15;
        
        $this->db->order_by('Faddtime', "DESC");
        $query = $this->db->get('storage_list','15',$page_count);
        $data = $query->result_array();
        
        foreach($data as $k=>$v){
            //查仓库
            $depot_name = $this->depot_model->get_depot($data[$k]['enter_depot']);
            $source_depot_name = $this->depot_model->get_depot($data[$k]['source_depot']);
            
            $data[$k]['depot_id'] = $data[$k]['enter_depot'];
            $data[$k]['enter_depot'] = $depot_name['depot_name'];
            $data[$k]['source_depot'] = $source_depot_name['depot_name'];
            
            switch($data[$k]['storage_type']){
                case"1":
                    $data[$k]['storage_type'] = '供应商采购';
                    
                    //查出供应商
                    $supplier = $this->depot_model->get_supplier($data[$k]['supplier']);
                    $data[$k]['supplier'] = $supplier['supplier_name'];
                    
                break;
                
                case"2":
                    $data[$k]['storage_type'] = '销售退货';
                break;
                
                case"3":
                    $data[$k]['storage_type'] = '调拨入库';
                break;
                
                case"4":
                    $data[$k]['storage_type'] = '其他入库';
                break;
            }            
        }
        $back_data['storage_data'] = $data;
        return $back_data ;
    }
    
    //查询条件获取 入库列表数据
    public function get_all_storage_where_list(){
            $page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
            $page_count = ($page-1)*15;
            
            $search_data  = array(
                "search"=>@$_REQUEST['search'],
                "start_date"=>@$_REQUEST['start_date'],
                "end_date"=>@$_REQUEST['end_date'],
                "storage_type"=>@$_REQUEST['storage_type'],
                "status"=>@$_REQUEST['status']
            );
            
            $wherelist = array();
            if(!empty($search_data['search'])){
                $wherelist[] = "(a.Fstorage_sn like '%{$search_data['search']}%' or a.Fsn like '%{$search_data['search']}%' or b.Fgoods_id like '%{$search_data['search']}%' or b.Fsku_id like '%{$search_data['search']}%')";
            
                if(!empty($search_data['start_date'])){
                    $wherelist[] = "a.Fstorage_date >='{$search_data['start_date']}'";
                }
                if(!empty($search_data['end_date'])){
                    $wherelist[] = "a.Fstorage_date <='{$search_data['end_date']}'";
                }
                if(!empty($search_data['storage_type'])){
                    $wherelist[] = "a.Fstorage_type = '{$search_data['storage_type']}'";
                }
                if(!empty($search_data['status'])){
                    $wherelist[] = "a.Fstatus = '{$search_data['status']}'";
                }
                //组装查询条件
                if(count($wherelist) > 0){
                    $where = " where ".implode(' AND ' , $wherelist);
                }
                //判断查询条件
                $where = isset($where) ? $where : '';
            
                $get_count_sql = "SELECT DISTINCT a.Fid,a.Fstorage_sn,a.Fsn,a.Fsupplier,a.Fstorage_date,a.Fstorage_type,a.Fenter_depot,a.Fcount,a.Fname,a.Fbeizhu,a.Fstatus FROM t_storage_list a left join t_storage_list_detail b on a.Fstorage_sn=b.Fstorage_sn  {$where} ";
            
                $sql = $get_count_sql."order by a.Faddtime desc limit {$page_count},15";
            }
            else{
                if(!empty($search_data['start_date'])){
                    $wherelist[] = "Fstorage_date >='{$search_data['start_date']}'";
                }
                if(!empty($search_data['end_date'])){
                    $wherelist[] = "Fstorage_date <='{$search_data['end_date']}'";
                }
                if(!empty($search_data['storage_type'])){
                    $wherelist[] = "Fstorage_type = '{$search_data['storage_type']}'";
                }
                if(!empty($search_data['status'])){
                    $wherelist[] = "Fstatus = '{$search_data['status']}'";
                }
                //组装查询条件
                if(count($wherelist) > 0){
                    $where = " where ".implode(' AND ' , $wherelist);
                }
                //判断查询条件
                $where = isset($where) ? $where : '';
            
                $get_count_sql = "SELECT * FROM t_storage_list $where ";
            
                $sql = $get_count_sql."order by Faddtime desc limit {$page_count},15";
            }
            

            //读取仓库
            $check_data = $this->index_model->get_query($sql);
            
            foreach($check_data as $k=>$v){
                //查仓库
                $depot_name = $this->depot_model->get_depot($check_data[$k]['enter_depot']);
                $check_data[$k]['depot_id'] = $check_data[$k]['enter_depot'];
                $check_data[$k]['enter_depot'] = $depot_name['depot_name'];
            
                switch($check_data[$k]['storage_type']){
                    case"1":
                        $check_data[$k]['storage_type'] = '供应商采购';
                        //查出供应商
                        $supplier = $this->depot_model->get_supplier($check_data[$k]['supplier']);
                        $check_data[$k]['supplier'] = $supplier['supplier_name'];
                        break;
            
                    case"2":
                        $check_data[$k]['storage_type'] = '销售退货';
                        break;
            
                    case"3":
                        $check_data[$k]['storage_type'] = '调拨入库';
                        break;
            
                    case"4":
                        $check_data[$k]['storage_type'] = '其他入库';
                        break;
                }
            }
            $back_data = array(
                "storage_data"=>$check_data,
                "get_count_sql"=>$get_count_sql,
            );
            $return_data = array_merge($back_data,$search_data);
          return $return_data;
    }
    
    //删除入库单
    public function delete_storage(){
        $storage_sn = @$_REQUEST['storage_sn'];
        $depot_id = @$_REQUEST['depot_id'];
        
        $check_sn = $this->get_sn_storage($storage_sn);
       
        
        $this->db->where('Fstorage_sn', $storage_sn);
        $this->db->delete('storage_list');
        
        if($check_sn['status']=='1'){
            //删除库存数据
            //先查出入库详情数据
            $check_data = $this->check_storage_sn($storage_sn,'','1');
            foreach($check_data as $k=>$v){
                 
                $this->db->set('Fcount', 'Fcount-'.$check_data[$k]['count'], FALSE);
                $this->db->set('Ftotal_count', 'Ftotal_count-'.$check_data[$k]['count'], FALSE);
                $this->db->set('Fhis_count', 'Fhis_count-'.$check_data[$k]['count'], FALSE);
                $this->db->where("Fsku_id",$check_data[$k]['sku_id']);
                $this->db->where("Fdepot_id",$depot_id);
                $this->db->update('stock_list');
            } 
        }
        
           
        //删除入库详情数据
           $this->db->where('Fstorage_sn', $storage_sn);
           $this->db->delete('storage_list_detail');
        return true;
    }
    
    public function update_storage_images(){
        $upload_images = @$_REQUEST['upload_images'];
        $sn = $_REQUEST['sn'];
        $this->db->set('Fupload_images', $upload_images);
        $this->db->where("Fstorage_sn",$sn);
        $this->db->update('storage_list');
        return true;
    }
    
    public function get_sn_storage($storage_sn){
        $query = $this->db->get_where('storage_list',array("Fstorage_sn"=>$storage_sn));
        $data = $query->row_array();
        return $data;
    }
}
