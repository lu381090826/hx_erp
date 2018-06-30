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
   <script language="javascript" src="/style/js/LodopFuncs.js"></script>
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
		    window.onload = function () { 
			    
	    		 $.ajax({     
	                 type:"POST",       
	                 url:"/depot/offer/get_print",
	                 dataType:"json",
	                 cache:false,
	                 beforeSend: function() {
	                 },     
	                 success:function(data){ 
		   		    	  var print_count = LODOP.GET_PRINTER_COUNT();
			   		 
				    	  for(i=0;i<print_count;i++){	
				    		  $("#odo").append("<OPTION value='"+i+"'>"+LODOP.GET_PRINTER_NAME(i)+"</OPTION>");
				    		  $("#package").append("<OPTION value='"+i+"'>"+LODOP.GET_PRINTER_NAME(i)+"</OPTION>");
					    	  if(data.data.odo==LODOP.GET_PRINTER_NAME(i)){
					    		  $("#odo").val(i);
						      }
					    	  if(data.data.package==LODOP.GET_PRINTER_NAME(i)){
					    		  $("#package").val(i);
						      }
					      }

	                 },   
	        
	              });
			}

		    function change_print(type,value){

		    	if(value==''){
		    		return;
			     }
			     
			    var value = LODOP.GET_PRINTER_NAME(value);
	    		 $.ajax({     
	                 type:"POST",       
	                 url:"/depot/offer/change_print",
	                 dataType:"json",
	                 data :{"type":type,"value":value},
	                 cache:false,
	                 beforeSend: function() {
	                 },     
	                 success:function(data){ 

	                 },   
	        
	              });
			}
   </SCRIPT>
   <title>库存列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form id="print"  action="/depot/offer/odo_detail_list_where_view" method="post">

<input type="text" name="search" id="search" value="<?php echo @$search_data['search'];?>"  placeholder="销售单号/客户姓名/电话/sku" class="input-text lh25" size="35">
<input type="text" name="start_date" id="start_date" value="<?php echo @$search_data['start_date'];?>"  placeholder="出库开始日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="12">
<input type="text" name="end_date" id="end_date" value="<?php echo @$search_data['end_date'];?>"  placeholder="出库结束日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="12">

<input type="submit" name="button" class="btn btn82 btn_search"  value="查询"> <span style='color:red;'>*支持模糊搜索</span>


</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="10%">出库单号</th>
               <th width="5%">出库档口</th>
               <th width="10%">销售单号</th>
               <th width="10%">报货单号</th>
               <th width="8%">客户姓名</th>
               <th width="8%">客户电话</th>
               <th width="8%">款号</th>
               <th width="5%">颜色</th>  
               <th width="5%">尺码</th>
               <th width="5%">报货数量</th>
               <th width="5%">出库数量</th>
               <th width="10%">出库日期</th>
              </tr>
 
           <?php 
           foreach($odo_detail_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id=""> 
                <td ><?php echo $odo_detail_data[$k]['odo_sn']?></td>
                <td ><?php echo $odo_detail_data[$k]['shop_name']?></td>               
                <td ><?php echo $odo_detail_data[$k]['sell_order']?></td>
                <td ><?php echo $odo_detail_data[$k]['allocate_order']?></td>
                <td ><?php echo $odo_detail_data[$k]['client_name']?></td>
                <td ><?php echo $odo_detail_data[$k]['client_phone']?></td>
                <td ><?php echo $odo_detail_data[$k]['spu']?></td>
                <td ><?php echo $odo_detail_data[$k]['color']?></td>
                <td ><?php echo $odo_detail_data[$k]['size']?></td>
                <td ><?php echo $odo_detail_data[$k]['allocate_count']?></td>
                <td ><?php echo $odo_detail_data[$k]['send_count']?></td>
                <td ><?php echo $odo_detail_data[$k]['odo_date']?></td>
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

$("#status").val('<?php echo @$search_data['status'];?>');
</script>
 </body>
 </html>
  
