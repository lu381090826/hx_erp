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
   <title>库位列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form action="cardlist.php" method="post">
<a href="/depot/depot/add_pos_view"><input type="button" name="button" class="btn btn82 btn_add"  value="加仓位">　</a>
<input type="text" name="search" id="search" value="<?php echo @$search;?>"  placeholder="请输入库位名字/库位代号" class="input-text lh25" size="30">
<input type="button" name="button" class="btn btn82 btn_search" onclick="check()" value="查询"> <span style='color:red;'>*支持模糊搜索</span>　　　
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="5%">id</th>
               <th width="10%">库位名字</th>
               <th width="10%">库位代码</th>
               <th width="10%">所属仓库</th>               
               <th width="20%">备注</th>
               <th width="10%">添加时间</th>
               <th width="20%">操作</th>         
              </tr>
              <?php 
              foreach($depot_data as $k=>$v){
              ?>
              <tr class='tr' align='center' id="d<?php echo $depot_data[$k]['id']?>">  
                <td ><?php echo $depot_data[$k]['id']?></td>
                <td ><?php echo $depot_data[$k]['pos_name'];?></td>
                <td ><?php echo $depot_data[$k]['pos_code']?></td>
                <td ><?php echo $depot_data[$k]['did']?></td>               
                <td ><?php echo $depot_data[$k]['beizhu']?></td>
                <td ><?php echo $depot_data[$k]['addtime']?></td>
                <td ><a href="/depot/depot/add_pos_view?id=<?php echo $depot_data[$k]['id']?>">修改</a>　　<a href="#" onclick="delete_pos('<?php echo $depot_data[$k]['id']?>')">删除</a></td>
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
  
