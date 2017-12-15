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
  
		    function delete_depot(id){
		    	if(window.confirm('你确定要删除这个仓库吗？')){
		    		 $.ajax({     
		                 type:"POST",       
		                 url:"/depot/depot/delete_depot",
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
   <title>仓库列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
<a href="/depot/depot/add_depot_view"><input type="button" name="button" class="btn btn82 btn_add"  value="加仓库">　</a>

  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="5%">id</th>
               <th width="10%">仓库名字</th>
               <th width="20%">仓库地址</th>
               <th width="10%">仓库联系人</th>               
               <th width="10%">联系人电话</th>
               <th width="20%">备注</th>
               <th width="10%">添加时间</th>
               <th width="20%">操作</th>         
              </tr>
              <?php 
              foreach($depot_data as $k=>$v){
              ?>
              <tr class='tr' align='center' id="d<?php echo $depot_data[$k]['id']?>">  
                <td ><?php echo $depot_data[$k]['id']?></td>
                <td ><?php echo $depot_data[$k]['depot_name'];?><span style="color:red;font-size:18px;"><?php if($depot_data[$k]['type']=='1'){echo "(默认仓库)";}?></span></td>
                <td ><?php echo $depot_data[$k]['depot_address']?></td>
                <td ><?php echo $depot_data[$k]['name']?></td>               
                <td ><?php echo $depot_data[$k]['mobile']?></td>
                <td ><?php echo $depot_data[$k]['beizhu']?></td>
                <td ><?php echo $depot_data[$k]['addtime']?></td>
                <td ><a href="/depot/depot/add_depot_view?id=<?php echo $depot_data[$k]['id']?>">修改</a>　　<a href="#" onclick="delete_depot('<?php echo $depot_data[$k]['id']?>')">删除</a></td>
              </tr>
              <?php }?>
              </table>
              <?php echo @$page_data;?>
        </div>
     </div>

   </div>
   <script type="text/javascript">

   </script>
 </body>
 </html>
  
