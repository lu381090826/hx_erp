<!doctype html>
 <html lang="zh-CN">
 <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="/style/css/common.css">
   <link rel="stylesheet" href="/style/css/main.css">
   <link rel="stylesheet" href="/style/css/page.css">
   <script type="text/javascript" src="/style/js/jquery.min.js"></script>
   <script type="text/javascript" src="/style/js/colResizable-1.3.min.js"></script>
   <script type="text/javascript" src="/style/js/common.js"></script>
   <script type="text/javascript" src="/style/js/Calendar.js"></script>
   <SCRIPT type="text/javascript">
  
		    function delete_storage(storage_sn,depot_id){
		    	if(window.confirm('你确定要删除这张入库单吗？')){
		    		 $.ajax({     
		                 type:"POST",       
		                 url:"/depot/storage/delete_storage",
		                 dataType:"json",
		                 data :{"storage_sn":storage_sn,"depot_id":depot_id},
		                 cache:false,
		                 beforeSend: function() {
		                 },     
		                 success:function(data){ 
		                	 $("#d"+storage_sn).remove(); 
		                	 alert(data.msg);		                	 
		                 },   
		        
		              });
		          }else{
		             //alert("取消");
		             return false;
		         }
            }

		    function check_storage(storage_sn){
		    	if(window.confirm('你确定要审核这张入库单吗？')){
		    		 $.ajax({     
		                 type:"POST",       
		                 url:"/depot/storage/check_add_sku",
		                 dataType:"json",
		                 data :{"storage_sn":storage_sn},
		                 cache:false,
		                 beforeSend: function() {
		                 },     
		                 success:function(data){
			                 if(data.result==1){
			                	 alert(data.msg);	
			                	 $("#check_status").text('');
				              }
			                 else{
			                	 alert(data.msg);	
				            } 
		                	 	                	 
		                 },   
		        
		              });
		          }else{
		             //alert("取消");
		             return false;
		         }
            }
   </SCRIPT>
   <title>入库单列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form action="/depot/storage/storage_list_where_view" method="get">
<a href="/depot/storage/add_storage_view"><input type="button" name="button" class="btn btn82 btn_add"  value="入库单">　</a>

<input type="text" name="search" id="search" value="<?php echo @$data['search'];?>"  placeholder="单号/sku/spu" class="input-text lh25" size="20">

<input type="text" name="start_date" id="start_date" value="<?php echo @$data['start_date'];?>"  placeholder="开始日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">

<input type="text" name="end_date" id="end_date" value="<?php echo @$data['end_date'];?>"  placeholder="结束日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">
<SELECT class="select" id="storage_type" name="storage_type" style="width:120px;">
                    <OPTION value="">全部入库类型</OPTION>
                    <OPTION value="1">供应商采购</OPTION>
                    <OPTION value="2">销售退货</OPTION>
                    <OPTION value="3">调拨入库</OPTION>
                    <OPTION value="4">其他入库</OPTION>
</SELECT>
<SELECT class="select" id="status" name="status" style="width:120px;">
                    <OPTION value="">全部状态</OPTION>
                    <OPTION value="1">已审核</OPTION>
                    <OPTION value="2">未审核</OPTION>
</SELECT>
<input type="submit" name="button" class="btn btn82 btn_search"  value="查询"> <span style='color:red;'>*支持模糊搜索</span>　　　
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="10%">入库单号</th>
               <th width="10%">手工单号</th>
               <th width="10%">入库日期</th>
               <th width="10%">入库类型</th>  
               <th width="10%">进入仓库</th>             
               <th width="10%">入库总数量</th>
               <th width="10%">制单人</th>
               <th width="15%">备注</th>
               <th width="20%">操作</th>         
              </tr>
              <?php 
              foreach(@$data['storage_data'] as $k=>$v){
              ?>
              <tr class='tr' align='center' id="d<?php echo $data['storage_data'][$k]['storage_sn']?>">  
                <td ><?php echo $data['storage_data'][$k]['storage_sn'];?></td>
                <td ><?php echo $data['storage_data'][$k]['sn'];?></td>
                <td ><?php echo $data['storage_data'][$k]['storage_date']?></td>
                <td ><?php echo $data['storage_data'][$k]['storage_type']?><?php if($data['storage_data'][$k]['storage_type']=='调拨入库'){echo "（".@$data['storage_data'][$k]['source_depot']."）";}elseif($data['storage_data'][$k]['storage_type']=='供应商采购'){echo "（".@$data['storage_data'][$k]['supplier']."）";}?></td>               
                <td ><?php echo $data['storage_data'][$k]['enter_depot']?></td>
                <td ><?php echo $data['storage_data'][$k]['count']?></td>
                <td ><?php echo $data['storage_data'][$k]['name']?></td>
                <td ><?php echo $data['storage_data'][$k]['beizhu']?></td>
                <td ><a href="/depot/storage/storage_list_detail_view?storage_sn=<?php echo $data['storage_data'][$k]['storage_sn'];?>">详情</a>　　<a href="#" onclick="delete_storage('<?php echo $data['storage_data'][$k]['storage_sn']?>','<?php echo $data['storage_data'][$k]['depot_id']?>')">删除</a>　　<a href="#" id="check_status" onclick="check_storage('<?php echo $data['storage_data'][$k]['storage_sn']?>')"><?php if($data['storage_data'][$k]['status']==1){echo "";}else{echo"审核";}?></a></td>
              </tr>
              <?php }?>
              </table>
            <?php echo @$page_data;?>
        </div>
     </div>

   </div>
   <script type="text/javascript">

   $("#storage_type").val('<?php echo @$data['storage_type'];?>');
   $("#status").val('<?php echo @$data['status'];?>');
   </script>
 </body>
 </html>
  
