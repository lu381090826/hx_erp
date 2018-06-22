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
 
 <form  action="/depot/stock/change_stock_where_view" method="get">
<a href="/depot/storage/add_storage_view"><input type="button" name="button" class="btn btn82 btn_add"  value="入库单">　</a>

<input type="text" name="search" id="search" value="<?php echo @$search_data['search'];?>"  placeholder="sku" class="input-text lh25" size="20">

<SELECT class="select" id="depot_id" name="depot_id" style="width:150px;">
                    <OPTION value="">全部仓库</OPTION>
                    <?php 
                      foreach(@$depot_data as $da=>$dv){
                          echo '<OPTION value="'.$depot_data[$da]['id'].'">'.$depot_data[$da]['depot_name'].'</OPTION>';
                      }
                    ?>
</SELECT>
<input type="submit" name="button" class="btn btn82 btn_search"  value="查询"> 
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="20%">skuid</th>
               <th width="20%">款号</th>
               <th width="10%">颜色</th>             
               <th width="10%">尺寸</th>
               <th width="10%">总库存</th>
               <th width="10%">可用库存</th> 
               <th width="10%">仓库</th>
               <th width="10%">操作</th>     
              </tr>
 
           <?php 
           foreach($stock_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id="">  
                <td ><?php echo $stock_data[$k]['sku_id']?></td>
                <td ><?php echo $stock_data[$k]['goods_id']?></td>
                <td ><?php echo $stock_data[$k]['color']?></td>               
                <td ><?php echo $stock_data[$k]['size']?></td>
                <td id="<?php echo @$stock_data[$k]['id']?>"><?php echo $stock_data[$k]['total_count']?></td>  
                <td id="count<?php echo @$stock_data[$k]['id']?>"><?php echo $stock_data[$k]['count']?></td>              
                <td ><?php echo @$stock_data[$k]['depot_id']?></td>
                <td ><input type="text" name="number" id ="<?php echo @$stock_data[$k]['id']?>" value="" onchange="change_count('<?php echo @$stock_data[$k]['id']?>',this.value,'<?php echo $stock_data[$k]['count'];?>','<?php echo $stock_data[$k]['total_count'];?>')" placeholder="输入修正后数量" class="input-text lh25" size="12"></td>
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
   function check(){
	   var search = $("#search").val();
	   window.location.href = "/depot/depot/pos_list_view?search="+search
	   
  }
function change_pos(pos_id,id){
		 $.ajax({
             url:"/depot/stock/change_pos",
             type:"POST",
             data:{"id":id,"pos_id":pos_id},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                          
             }
         });
}

function change_count(id,change_count,count,total_count){
	var data_count =  parseInt(count)+parseInt(change_count)-parseInt(total_count);
	 $.ajax({
       url:"/depot/stock/change_stock",
       type:"POST",
       data:{"id":id,"change_count":change_count,"total_count":total_count},
       dataType:"json",
       async:false,
       error: function() {
           //alert('服务器超时，请稍后再试');
       },
       success:function(data){   
           if(data.result==1){
               
               
               $("#"+id).html(change_count);
               $("#count"+id).html(data_count);
           }              
           else{
               alert('修改失败');
          }         
       }
   });
}

   $("#depot_id").val('<?php echo @$search_data['depot_id'];?>');
   </script>
 </body>
 </html>
  
