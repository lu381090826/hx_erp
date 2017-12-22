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
   <title>入库单列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form  method="post">
<a href="/depot/storage/add_storage_view"><input type="button" name="button" class="btn btn82 btn_add"  value="入库单">　</a>

<input type="text" name="search" id="search" value="<?php echo @$search;?>"  placeholder="入库单号/sku/spu" class="input-text lh25" size="20">

<input type="text" name="start_date" id="start_date" value=""  placeholder="开始日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">

<input type="text" name="end_date" id="end_date" value=""  placeholder="结束日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">
<SELECT class="select" id="storage_type" name="storage_type" style="width:120px;">
                    <OPTION value="">入库类型</OPTION>
</SELECT>
<input type="button" name="button" class="btn btn82 btn_search" onclick="" value="查询"> <span style='color:red;'>*支持模糊搜索</span>　　　
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="10%">入库单号</th>
               <th width="10%">入库日期</th>
               <th width="10%">入库类型</th>  
               <th width="10%">进入仓库</th>             
               <th width="10%">入库总数量</th>
               <th width="10%">制单人</th>
               <th width="20%">备注</th>
               <th width="20%">操作</th>         
              </tr>
              <?php 
              foreach($storage_data as $k=>$v){
              ?>
              <tr class='tr' align='center' id="d<?php echo $storage_data[$k]['id']?>">  
                <td ><?php echo $storage_data[$k]['pos_name'];?></td>
                <td ><?php echo $storage_data[$k]['pos_code']?></td>
                <td ><?php echo $storage_data[$k]['did']?></td>               
                <td ><?php echo $storage_data[$k]['beizhu']?></td>
                <td ><?php echo $storage_data[$k]['beizhu']?></td>
                <td ><?php echo $storage_data[$k]['beizhu']?></td>
                <td ><?php echo $storage_data[$k]['beizhu']?></td>
                <td ><a href="/depot/storage/add_storage_view">详情</a>　　<a href="#" onclick="delete_pos('<?php echo $storage_data[$k]['id']?>')">删除</a></td>
              </tr>
              <?php }?>
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
   </script>
 </body>
 </html>
  
