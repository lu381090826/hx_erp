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
<a href="/depot/storage/add_storage_view"><input type="button" name="button" class="btn btn82 btn_add"  value="入库单">　</a>

<input type="text" name="search" id="search" value="<?php echo @$search;?>"  placeholder="sku" class="input-text lh25" size="20">

<SELECT class="select" id="storage_type" name="storage_type" style="width:150px;">
                    <OPTION value="">全部仓库</OPTION>
                    <?php 
                      foreach(@$depot_data as $da=>$dv){
                          echo '<OPTION value="'.$depot_data[$da]['id'].'">'.$depot_data[$da]['depot_name'].'</OPTION>';
                      }
                    ?>
</SELECT>
<input type="button" name="button" class="btn btn82 btn_search" onclick="" value="查询"> 
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="5%">款式图</th>
               <th width="10%">skuid</th>
               <th width="10%">款号</th>
               <th width="5%">颜色</th>             
               <th width="5%">尺寸</th>
               <th width="10%">当天入库数量</th>
               <th width="5%">总入库</th>
               <th width="5%">总出库</th>
               <th width="5%">总库存</th>
               <th width="5%">可用库存</th> 
               <th width="5%">未配数量</th>  
               <th width="5%">已配数量</th>               
               <th width="10%">所属仓库</th> 
               <th width="10%">所属仓位</th>
               <th width="5%">操作</th>     
              </tr>
 
           <?php 
           foreach($stock_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id="">  
                <td ><a href="<?php echo $stock_data[$k]['pic']?>" target="_blank">点击查看</a></td>
                <td ><?php echo $stock_data[$k]['sku_id']?></td>
                <td ><?php echo $stock_data[$k]['goods_id']?></td>
                <td ><?php echo $stock_data[$k]['color']?></td>               
                <td ><?php echo $stock_data[$k]['size']?></td>
                <td ><?php echo @$stock_data[$k]['date_count']?></td>
                <td ><?php echo $stock_data[$k]['his_count']?></td>
                <td ><?php echo $stock_data[$k]['out_count']?></td>
                <td ><?php echo $stock_data[$k]['total_count']?></td>
                <td ><?php echo $stock_data[$k]['count']?></td>
                <td ><?php echo $stock_data[$k]['weipei_count']?></td>
                <td ><?php echo $stock_data[$k]['send_count']?></td>                
                <td ><?php echo @$stock_data[$k]['depot_id']?></td>
                <td >
                <SELECT class="select" id="storage_type" name="storage_type" onchange="change_pos(this.value,'<?php echo $stock_data[$k]['id'];?>')" style="width:80px;">
                    <OPTION value="">选库位</OPTION>
                    <?php 
                      foreach(@$stock_data[$k]['pos_data'] as $pa=>$pv){
                          if($stock_data[$k]['pos_id']==$stock_data[$k]['pos_data'][$pa]['id']){
                              echo '<OPTION value="'.$stock_data[$k]['pos_data'][$pa]['id'].'" selected="selected">'.$stock_data[$k]['pos_data'][$pa]['pos_name'].'</OPTION>';
                          }
                          else{
                              echo '<OPTION value="'.$stock_data[$k]['pos_data'][$pa]['id'].'" >'.$stock_data[$k]['pos_data'][$pa]['pos_name'].'</OPTION>';
                          }                         
                      }
                    ?>
                </SELECT>
                </td>
                <td >详情</td>
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
   </script>
 </body>
 </html>
  
