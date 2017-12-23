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
<a href="/depot/depot/add_pos_view"><input type="button" name="button" class="btn btn82 btn_add"  value="入库单">　</a>

<input type="text" name="search" id="search" value="<?php echo @$search;?>"  placeholder="sku/spu" class="input-text lh25" size="20">

<SELECT class="select" id="storage_type" name="storage_type" style="width:120px;">
                    <OPTION value="">按sku显示</OPTION>
                    <OPTION value="">按spu显示</OPTION>
</SELECT>
<SELECT class="select" id="storage_type" name="storage_type" style="width:120px;">
                    <OPTION value="">大分类</OPTION>
</SELECT>
<SELECT class="select" id="storage_type" name="storage_type" style="width:120px;">
                    <OPTION value="">小分类</OPTION>
</SELECT>
<SELECT class="select" id="storage_type" name="storage_type" style="width:120px;">
                    <OPTION value="">状态</OPTION>
                    <OPTION value="1">上架</OPTION>
                    <OPTION value="2">下架</OPTION>
</SELECT>
<SELECT class="select" id="storage_type" name="storage_type" style="width:150px;">
                    <OPTION value="1">按数量由多到少排列</OPTION>
                    <OPTION value="2">按数量由少到多排列</OPTION>
</SELECT>
<SELECT class="select" id="storage_type" name="storage_type" style="width:150px;">
                    <OPTION value="1">全部仓库</OPTION>
</SELECT>
<input type="button" name="button" class="btn btn82 btn_search" onclick="" value="查询"> 
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="10%">商品图片</th>
               <th width="10%">商品款号</th>
               <th width="10%">颜色</th>             
               <th width="10%">尺寸</th>
               <th width="10%">总入库</th>
               <th width="10%">总出库</th>
               <th width="10%">上新日期</th>
               <th width="10%">总库存</th>
               <th width="10%">可用库存</th>     
              </tr>
 

              <tr class='tr' align='center' id="">  
                <td ><img src="/style/images/a.png" width="100" height="100"></td>
                <td >TS001</td>
                <td >蓝色</td>               
                <td >170A</td>
                <td >888</td>
                <td >666</td>.
                <td >2017-12-01</td>
                <td >NNN</td>
                <td >222</td>
              </tr>
              <tr class='tr' align='center' id="">  
                <td ><img src="/style/images/a.png" width="100" height="100"></td>
                <td >TS001</td>
                <td >蓝色</td>               
                <td >175A</td>
                <td >888</td>
                <td >666</td>.
                <td >2017-12-01</td>
                <td >NNN</td>
                <td >222</td>
              </tr>
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
  
