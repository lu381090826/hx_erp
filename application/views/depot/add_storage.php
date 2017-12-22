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
   <script type="text/javascript">
		 
   </script>
   <title>添加入库单</title>
 </head>
 <body style="height:100%">
     <div id="forms" class="mt10">
        <div class="box">
          <div class="box_border">
           <div class="box_top"><b class="pl15">添加入库单</b></div>
           
            <div class="box_center">
            <form action="" method="post" class="jqtransform" enctype="multipart/form-data">
               <table class="form_table pt15 pb15" width="100%" border="0" cellpadding="0" cellspacing="0" id="add_tr">
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 入库单号:</td>
                  <td style="width:100px;"><input type="text" name="sb" id="sn"  value="<?php echo @$pos_data['sn'];?>" class="input-text lh25" size="40"></td> 
                  <td class="td_right"><span class="bitian">*</span> 入库类型:</td>
                  <td>
                  <select name="storage_type" id="storage_type" class="select" >
                  <option value="" >请选择入库类型</option>
                  <option value="1" >供应商采购</option>
                  <option value="2" >销售退货</option>
                  <option value="3" >调拨入库</option>
                  <option value="4" >其他入库</option>
                  </select>
                  </td>            
               </tr>
               <tr> 
                  <td class="td_right"><span class="bitian">*</span> 入库日期:</td>
                  <td><input type="text" name="date" id="date"  value="<?php echo @$pos_data['date'];?>" class="input-text lh25" size="40"></td>
                  <td class="td_right"><span class="bitian">*</span> 经办人:</td>
                  <td><input type="text" name="sb" id="sn"  value="<?php echo @$pos_data['sn'];?>" class="input-text lh25" size="40"></td>
               </tr>               
               <tr >
               <td class="td_right"><span class="bitian">*</span> 进入仓库:</td>
               <td><select name="enter_depot" id="enter_depot" class="select" ><?php foreach(@$depot_data as $k=>$v){echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?></select></td>
               <td class="td_right"><span class="bitian">*</span> 调拨仓库:</td><td><select name="source_depot" id="source_depot" class="select" ><?php foreach(@$depot_data as $k=>$v){echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?></select></td>
               </tr>
               
               <tr >
               <td class="td_right"><span class="bitian">*</span> 供应商:</td>
               <td>
               <select name="supplier" id="supplier" class="select" >
               <option value="">请选择供应商</option>
               <?php foreach(@$depot_data as $k=>$v)
               {echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?>
               </select>
               </td>
               <td class="td_right"><span class="bitian">*</span> 退单号:</td>
               <td><select name="return_sn" id="return_sn" class="select" >
               <option value="">请选择退单号</option>
               </select>
               </td>
               </tr>            
               <tr> 
                  <td class="td_right">备注:</td>
                  <td><input type="text" name="beizhu" id="beizhu"  value="<?php echo @$pos_data['date'];?>" class="input-text lh25" size="40"></td>
                  <td class="td_right">上传图片:</td>
                  <td><input type="file" name="file" id="file"  value="<?php echo @$pos_data['sn'];?>" class="input-text lh25" size="60"></td>
               </tr>
               </table>

              </div>
               </form>
               
            </div>
          </div>
        </div>
        
<div style="margin-top:1%;">
<div class="box_top"><b class="pl15" >搜索列表</b></div>
<input type="text" name="search" id="search" value="<?php echo @$search;?>"  placeholder="请输入款号spu" class="input-text lh25" size="20">
<input type="button" name="button" class="btn btn82 btn_search" onclick="check()" value="搜索">
        <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="20%">商品图片</th>
               <th width="20%">商品款号</th>
               <th width="10%">商品颜色</th>               
               <th width="10%">商品尺码</th>
               <th width="5%">添加数量</th>
               <th width="20%">备注</th>
               <th width="10%">操作</th>         
              </tr>

              </table>
<?php echo @$page_data;?>
        </div>
     </div>
</div>

<div style="margin-top:2%;">
<div class="box_top"><b class="pl15">已添加列表</b></div>
        <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="add_table">
              <tr>
               <th width="20%">商品图片</th>
               <th width="20%">商品款号</th>
               <th width="10%">商品颜色</th>               
               <th width="10%">商品尺码</th>
               <th width="5%">入库数量</th>
               <th width="20%">备注</th>
               <th width="10%">操作</th>         
              </tr>
              </table>
        </div>
     </div>
</div>

<div  style="margin-bottom:70px;">
<?php echo @$page_data;?>
</div>
<div class="search_bar_btn" id="set_button"> 

<div>
<input type="hidden" name="hide_id" id="hide_id"  value="<?php echo @$pos_data['id'];?>" id="hidden1"/> 
<input type="button" value="提交" onclick="add_pos()" class="btn btn82 btn_save2"> 
</div> 
</div>  

<script type="text/javascript">
   
   function check(){
	   $(".list_table").append('<tr class="tr" align="center"><td ><img src="/style/images/a.png" width="100" height="100"></td><td >TS001</td><td >红色</td><td >175A</td><td ><input type="text" name="number1" id="number1"  placeholder="请输入数量" class="input-text lh25" size="8"></td><td><input type="text" name="beizhu1" id="beizhu1"  placeholder="请输入备注" class="input-text lh25" size="20"></td><td ><a href="javascript:void(0);" onclick="add()">添加</a></td></tr>');	  
   }

  function add(){
	  var number = $("#number1").val();
	  var beizhu = $("#beizhu1").val();
	  $(".add_table").append('<tr class="tr" id="add_number1" align="center"><td ><img src="/style/images/a.png" width="100" height="100"></td><td >TS001</td><td >红色</td><td >175A</td><td >'+number+'</td><td >'+beizhu+'</td><td ><a href="javascript:void(0);" onclick="delete_tr()">删除</a></td></tr>');
  }

  function delete_tr(){
	  $("#add_number1").remove();
  }
   $(document).ready(function(){
	   $("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	   $("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	   $("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	   $('#storage_type').change(function(){
	       var type =  $(this).children('option:selected').val();
	        switch(type){
	        case"1":
	        	  $("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	  $("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	  $("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
		    
	        case"2":
	        	$("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	$("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
		    
	        case"3":
	        	$("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	$("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
		    
	        case"4":
	        	$("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	$("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
	        }
	   })
  })
	   
   function add_pos(){
	   var pos_name = $("#pos_name").val();
	   var pos_code = $("#pos_code").val();
	   var beizhu = $("#beizhu").val();
	   var did = $("#did").val();
	   var id = $("#hide_id").val();

	   if(!pos_name||!pos_code||!did){
		   alert('仓库名字、仓库地址和仓库联系人必填');return;
	   }
		 $.ajax({
             url:"/depot/depot/add_pos",
             type:"POST",
             data:{"id":id,"pos_name":pos_name,'pos_code':pos_code,"did":did,"beizhu":beizhu},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result=='1'){
                    alert(data.msg);
                    window.history.back(-1);
              }
              else{
                    alert(data.msg);
               }           
             }
         });
  }
   
	$("#did").val('<?php echo @$pos_data['did'];?>');
 </script>
 </body>
 </html>
  
