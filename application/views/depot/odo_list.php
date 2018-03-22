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
  
		    function delete_pos(id){
		    	if(window.confirm('你确定要删除这个库位吗？')){
		    		 $.ajax({     
		                 type:"POST",       
		                 url:"/depot/depot/delete_pos",
		                 dataType:"json",
		                 data :{"id":id},
		                 cache:false,
		                 beforeSend: function() {
		                 },     
		                 success:function(data){ 
		                	 $("#d"+id).remove(); 
		                	 alert(data.msg);		                	 
		                 },   
		        
		              });
		          }else{
		             //alert("取消");
		             return false;
		         }
            }


   </SCRIPT>
   <title>库存列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form  method="post">

<input type="text" name="search" id="search" value="<?php echo @$search;?>"  placeholder="订单号/sku/spu" class="input-text lh25" size="20">
<input type="text" name="odo_date" id="odo_date" value="<?php echo @$data['odo_date'];?>"  placeholder="出库日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">
<SELECT class="select" id="status" name="status" style="width:120px;">
                    <OPTION value="">全部状态</OPTION>
                    <OPTION value="4">等待打单</OPTION>
                    <OPTION value="3">打单完成</OPTION>
                    <OPTION value="2">拣货完成</OPTION>
                    <OPTION value="1">已出库</OPTION>
</SELECT>
<input type="button" name="button" class="btn btn82 btn_search" onclick="" value="查询"> <span style='color:red;'>*支持模糊搜索</span>


</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="10%">出库单号</th>
               <th width="5%">制单人</th>
               <th width="8%">出库类型</th>
               <th width="8%">出库仓库</th>
               <th width="8%">出库数量</th>
               <th width="8%">出库日期</th>  
               <th width="10%">创建时间</th>
               <th width="15%">备注</th>                          
               <th width="10%">订单状态</th>
               <th width="15%">操作</th>
              </tr>
 
           <?php 
           foreach($odo_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id=""> 
                <td ><?php echo $odo_data[$k]['odo_sn']?></td>
                <td ><?php echo $odo_data[$k]['name']?></td>               
                <td ><?php echo $odo_data[$k]['enter_depot']?></td>
                <td ><?php echo $odo_data[$k]['source_depot']?></td>
                <td ><?php echo $odo_data[$k]['total_count']?></td>
                <td ><?php echo $odo_data[$k]['odo_date']?></td>
                <td ><?php echo $odo_data[$k]['create_time']?></td>
                <td ><?php echo $odo_data[$k]['beizhu']?></td>
                <td id="<?php echo $odo_data[$k]['odo_sn'];?>"><?php 
                   switch ($odo_data[$k]['status']){
                       case"4":
                             echo "<span>等待打单</span>";
                       break;
                       
                       case"3":
                           echo "<span style='color:blue;'>打单完成</span>";
                       break;
                       
                       case"2":
                           echo "<span style='color:#008000;'>拣货完成</span>";
                       break;
                               
                       case"1":
                           echo "<span style='color:red;'>已出库</span>";
                       break;
                }
                ?></td>
                <td ><a href="/depot/offer/odo_detail_view?odo_sn=<?php echo $odo_data[$k]['odo_sn']?>">详情</a>　　<a href="javascript:void(0);" onclick="change_status('3','<?php echo $odo_data[$k]['odo_sn']?>')" >打单</a>　　<a href="javascript:void(0);" onclick="change_status('2','<?php echo $odo_data[$k]['odo_sn']?>')">拣货</a>　　<a href="javascript:void(0);" onclick="change_status('1','<?php echo $odo_data[$k]['odo_sn']?>')">出库</a></td>
              </tr>
         <?php 
           }
         ?>
              </table>
            <?php echo @$page_data;?>
        </div>
     </div>

   </div>
<script type="text/javascript">

function peihuo(){
   var chk_value =[]; 
	$('input[name="cblist"]:checked').each(function(){ 
	chk_value.push($(this).val()); 
	});
	 if(chk_value.length==0){
           alert('请选择需要配货的报货单！');return;
     }

	 window.location.href= "/depot/offer/add_odo_view?id_list="+chk_value;
}
var isCheckAll = false;  
function swapCheck() {  
	   //取消全选
    if (isCheckAll) {  
        $("input[type='checkbox']").each(function() {  
            this.checked = false;  
        }); 
        isCheckAll = false;  
    } 
    //全选
    else {  
        $("input[type='checkbox']").each(function() {  
            this.checked = true;  
        });  
        isCheckAll = true;  
    }  
}  

function change_status(status,odo_sn){
	if(status==3){
		var text  = '你确定要打印这张出库单吗？';
    }
	else if(status==2){
		var text  = '你确定拣货完成了吗？';
   }
	else if(status==1){
		var text  = '你确定要出库了吗？';
   }   
	if(window.confirm(text)){
		 $.ajax({     
           type:"POST",       
           url:"/depot/offer/update_odo_status",
           dataType:"json",
           data :{"status":status,"odo_sn":odo_sn},
           cache:false,
           beforeSend: function() {
           },     
           success:function(data){ 
               if(data.result=='1'){
                   if(status==3){
                	   window.open('/depot/offer/print_list?odo_sn='+odo_sn); 
                   }
                   else if(status==2){
                       alert('拣货完成');
                   }
                   else if(status==1){
                	   alert('出库完成');
                   }
                   $("#"+odo_sn).html(data.data);
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
</script>
 </body>
 </html>
  
