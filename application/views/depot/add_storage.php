 <!doctype html>
 <html lang="zh-CN">
 <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="/style/css/common.css">
   <link rel="stylesheet" href="/style/css/main.css">
   <link rel="stylesheet" href="/style/css/page.css">
   <link rel="stylesheet" href="/assets/page/form/add.css">
   <link rel="stylesheet" href="/assets/plugin/images-input/images-input.css">
   <script type="text/javascript" src="/style/js/jquery.min.js"></script>
   <script type="text/javascript" src="/style/js/Calendar.js"></script>
   <script type="text/javascript" src="/style/js/colResizable-1.3.min.js"></script>
   <script type="text/javascript" src="/style/js/common.js"></script>
   <script type="text/javascript" src="/style/js/storage.js"></script>
   <script src="/assets/plugin/images-input/images-input.js"></script>
   <script type="text/javascript">
		 
   </script>
   <title>添加入库单</title>
 </head>
 <body style="height:100%">
     <div id="forms" class="mt10">
        <div class="box">
          <div class="box_border">
           <div class="box_top"><b class="pl15">添加入库单 </b><b class="pl15" style="float: right;padding-right: 15px;"><a href="#" onclick="javascript:history.go(-1);">返回上一页</a></b></div>
           
            <div class="box_center">
            <form action="" method="post" class="jqtransform" enctype="multipart/form-data">
               <table class="form_table pt15 pb15" width="100%" border="0" cellpadding="0" cellspacing="0" id="add_tr">
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 入库单号:</td>
                  <td style="width:100px;"><input type="text" name="storage_sn" id="storage_sn"  value="<?php echo isset($storage_data['storage_sn'])?$storage_data['storage_sn']:@$storage_sn;?>" disabled="true" style="background-color: rgba(10, 3, 31, 0.2);"class="input-text lh25" size="40"></td> 
                  <td class="td_right"><span class="bitian">*</span> 手工单号:</td>
                  <td style="width:100px;"><input type="text" name="sn" id="sn"  value="<?php echo @$storage_data['sn'];?>" class="input-text lh25" size="40"></td>
                  <td class="td_right"><span class="bitian">*</span> 入库日期:</td>
                  <td><input type="text" name="storage_date" id="storage_date" value="<?php echo isset($storage_data['storage_date'])?$storage_data['storage_date']:date("Y-m-d",time());?>" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true"  class="input-text lh25" size="20"></td>                             
               </tr>
               <tr> 
                  <td class="td_right"><span class="bitian">*</span> 入库类型:</td>
                  <td>
                  <select name="storage_type" id="storage_type" class="select" >
                  <option value="" >请选择入库类型</option>
                  <option value="1" >供应商采购</option>
                  <option value="3" >调拨入库</option>
                  <option value="4" >其他入库</option>
                  </select>
                  </td> 
               <td class="td_right"><span class="bitian">*</span> 进货仓库:</td>
               <td><select name="enter_depot" id="enter_depot" class="select" ><?php foreach(@$depot_data as $k=>$v){echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?></select></td>
                  <td class="td_right"><span class="bitian">*</span> 经办人:</td>
                  <td><input type="text" name="name" id="name"  value="<?php echo isset($storage_data['name'])?$storage_data['name']:@$_SESSION['name'];?>" class="input-text lh25" size="20"></td>
               </tr>               
               <tr >
               <td class="td_right"><span class="bitian">*</span> 供应商:</td>
               <td>
               <select name="supplier" id="supplier" class="select1" >
               <option value="">请选择供应商</option>
               </select>
               <input type="text" name="check_supplier" id="check_supplier" onchange="get_supplier(this.value)" placeholder="搜索供应商"  value="" class="input-text lh25" size="12">           
               </td>
               
               <td class="td_right"><span class="bitian">*</span> 出货仓库:</td><td><select name="source_depot" id="source_depot" class="select" ><?php foreach(@$depot_data as $k=>$v){echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?></select></td>

               
               </tr>
           
               <tr> 
                  <td class="td_right"><div class="form-group">
                  <label>上传照片:</label>
                   <td><div id="images-input" name="images-input" path="/sell/Api/upload_base64" value=''></td>
                  </div>
                  </td>
                  <td class="td_right">备注:</td>
                  <td><input type="text" name="beizhu" id="beizhu"  value="<?php echo @$storage_data['beizhu'];?>" class="input-text lh25" size="40"></td>
               </tr>
               </table>

              </div>
               </form>
               
            </div>
          </div>
        </div>
        
<div style="margin-top:1%;">
<div class="box_top"><b class="pl15" >搜索列表</b></div>
<input type="text" name="spu" id="spu" value="<?php echo @$search;?>"  placeholder="请输入款号spu" class="input-text lh25" size="20">
<input type="button" name="button" class="btn btn82 btn_search" onclick="jump_page()" value="搜索">
<span id="notice" style="color:red;"></span>
        <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="20%">商品款号</th>
               <th width="10%">商品颜色</th>               
               <th width="10%">商品尺码</th>
               <th width="10%">添加数量</th>   
              </tr>
              </table>
<div id="page"></div>
        </div>
     </div>
</div>

<div style="margin-top:2%;">
<div class="box_top"><b class="pl15">已添加列表</b></div>
        <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="add_table">
              <tr>
               <th width="20%">商品款号</th>
               <th width="10%">商品颜色</th>               
               <th width="10%">商品尺码</th>
               <th width="10%">入库数量</th>
               <th width="20%">备注</th> 
               <th width="5%">删除</th>     
              </tr>
              <?php 
              if(!empty($storage_detail_data)){
                  foreach(@$storage_detail_data as $k=>$v){
                      echo '<tr class="dr" id="d'.$storage_detail_data[$k]['sku_id'].'" align="center"><td >'.$storage_detail_data[$k]['goods_id'].'</td><td >'.$storage_detail_data[$k]['color'].'</td><td >'.$storage_detail_data[$k]['size'].'</td><td ><input type="text" name="number1" id ="'.$storage_detail_data[$k]['sku_id'].'" value="'.$storage_detail_data[$k]['count'].'" onchange="change_count(this.id,this.value)" placeholder="请输入数量" class="input-text lh25" size="8"></td><td ><input type="text" name="beizhu" id ="'.$storage_detail_data[$k]['sku_id'].'"   value="'.$storage_detail_data[$k]['beizhu'].'" onchange="change_beizhu(this.id,this.value)" placeholder="请输入备注" class="input-text lh25" size=50"></td><td><a href="javascript:void(0)" onclick=delete_storage_sku_id("'.$storage_detail_data[$k]['sku_id'].'","'.$storage_detail_data[$k]['storage_sn'].'")>删除</a></td></tr>';
                      
                 }
               }
              ?> 
              </table>              
        </div>
     </div>
</div>

<div  id="s_page" style="margin-bottom:70px;">
<?php 
if(@$page_data){
   echo  $page_data;
}
?>
</div>
<div class="search_bar_btn" id="set_button"> 

<div>
<input type="hidden" name="hide_id" id="hide_id"  value="<?php echo @$pos_data['id'];?>" id="hidden1"/> 
<input type="button" value="提交" onclick="add_storage()" class="btn btn82 btn_save2"> 
</div> 
</div>  

<script type="text/javascript">
$(document).ready(function(){
	
	var storage_type ='<?php echo @$storage_data['storage_type'];?>'
    switch(storage_type){
    case"1":
    	  $("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
    	  $("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
    	  $("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
    break;
    
    case"2":
    	alert('销售退货入库流程正在开发中，请等待！');return;
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

	$("#storage_type").val(storage_type);
	$("#enter_depot").val('<?php echo @$storage_data['enter_depot'];?>');
	$("#supplier").val('<?php echo @$storage_data['supplier'];?>');
	$("#source_depot").val('<?php echo @$storage_data['source_depot'];?>');
	change_dr();
})

function delete_storage_sku_id(sku_id,storage_sn){
	//要删除的原入库数量
	var before_count = $("#"+sku_id).val();
	var enter_depot = '<?php echo @$storage_data['enter_depot'];?>';
	var storage_type ='<?php echo @$storage_data['storage_type'];?>'
	if(window.confirm('你确定要删除这个SKU吗？')){
		 $.ajax({     
           type:"POST",       
           url:"/depot/storage/delete_storage_sku",
           dataType:"json",
           data :{"storage_sn":storage_sn,"sku_id":sku_id,"before_count":before_count,"enter_depot":enter_depot},
           cache:false,
           beforeSend: function() {
           },     
           success:function(data){ 
          	 $("#d"+sku_id).remove(); 
          	 alert(data.msg);		                	 
           },   
  
        });
    }else{
       //alert("取消");
       return false;
   }
}

$("#images-input").imagesInput({
    change:change
});
function change(value){
    console.log(value);
}

function get_supplier(content){
	 $.ajax({
         url:"/depot/storage/check_supplier",
         type:"POST",
         data:{"content":content},
         dataType:"json",
         async:false,
         error: function() {
             //alert('服务器超时，请稍后再试');
         },
         success:function(data){    
             if(data.result==1){
            	 $("#supplier").empty();
            	 $("#supplier").append("<option value='0'>请选择供应商</option>");

            	 for(var i =0;i<data.data.length;i++){
            		 $("#supplier").append("<option value='"+data.data[i].id+"'>"+data.data[i].supplier_name+"</option>");
                }
             }   
             else{
                 alert('搜索内容不存在');
            }                   
         }
     });
}
 </script>
 </body>
 </html>
  
