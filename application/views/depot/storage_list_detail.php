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
   <script type="text/javascript" src="/style/js/storage_detail.js"></script>
   <script src="/assets/plugin/images-input/detail_images.js"></script>
   <script type="text/javascript">
		 
   </script>
   <title>入库单详情</title>
 </head>
 <body style="height:100%">
     <div id="forms" class="mt10">
        <div class="box">
          <div class="box_border">
           <div class="box_top"><b class="pl15">入库单详情</b><b class="pl15" style="float: right;padding-right: 15px;"><a href="#" onclick="javascript:history.go(-1);">返回上一页</a></b></div>
           
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="show_table">
              <tr>
               <th width="10%">入库单号</th>
               <th width="10%">手工单号</th>
               <th width="10%">入库日期</th>
               <th width="15%">入库类型</th>  
               <th width="5%">进入仓库</th>             
               <th width="5%">入库总数量</th>
               <th width="5%">制单人</th>
               <th width="20%">备注</th>
               <th width="20%">图片</th>
              </tr>

              <tr class='lr' align='center'">  
                <td id="storage_sn"><?php echo $storage_data['storage_sn'];?></td>
                <td ><?php echo $storage_data['sn'];?></td>
                <td id="storage_date"><?php echo $storage_data['storage_date']?></td>
                <td ><?php echo $storage_data['storage_type']?><?php if($storage_data['storage_type']=='调拨入库'){echo "（".@$storage_data['source_depot']."）";}elseif($storage_data['storage_type']=='供应商采购'){echo "（".@$storage_data['supplier']."）";}?></td>               
                <td id="enter_depot"><?php echo $storage_data['enter_depot']?></td>
                <td ><?php echo $storage_data['count']?></td>
                <td ><?php echo $storage_data['name']?></td>
                <td ><textarea rows="11" cols="40" onchange="change_storage_beizhu()" placeholder="请输入备注" class="storage_beizhu"><?php echo @$storage_data['beizhu'];?></textarea></td>
                <td ><div id="images-input" name="images-input" path="/sell/Api/upload_base64" value='<?php echo $storage_data['upload_images']?>'></div></td>
              </tr>
              </table>
              
        </div>
     </div>

   </div>
               
            </div>
          </div>
        </div>
        
<div  <?php if(@$storage_data['status']==1&&$storage_data['storage_type']=='调拨入库'){$a = 'readonly="readonly"';echo 'style="display:none"';}else{$a='';}?>>
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
                  foreach(@$storage_detail_data as $k=>$v){
                      echo '<tr class="dr" id="d'.$storage_detail_data[$k]['sku_id'].'" align="center"><td >'.$storage_detail_data[$k]['goods_id'].'</td><td >'.$storage_detail_data[$k]['color'].'</td><td >'.$storage_detail_data[$k]['size'].'</td><td ><input type="text" name="number1" id ="'.$storage_detail_data[$k]['sku_id'].'" value="'.$storage_detail_data[$k]['count'].'" '.$a.' onchange="change_count(this.id,this.value)" placeholder="请输入数量" class="input-text lh25" size="8"></td><td ><input type="text" name="beizhu" id ="'.$storage_detail_data[$k]['sku_id'].'"   value="'.$storage_detail_data[$k]['beizhu'].'" onchange="change_beizhu(this.id,this.value)" placeholder="请输入备注" class="input-text lh25" size=50"></td><td><a href="javascript:void(0)" onclick=delete_storage_sku_id("'.$storage_detail_data[$k]['sku_id'].'","'.$storage_detail_data[$k]['storage_sn'].'")>删除</a></td></tr>';
                      
                 }
              ?> 
              </table>              
        </div>
     </div>
</div>

<div  id="s_page" style="margin-bottom:70px;">
<?php 

   echo  $page_data;
?>
</div>
 
<div class="search_bar_btn" id="set_button"> 
<input type="hidden" name="depot_id" id="depot_id"  value="<?php echo $storage_data['depot_id'];?>" /> 
<input type="hidden" name="source_depot_id" id="source_depot_id"  value="<?php echo $storage_data['source_depot_id'];?>" />
<input type="hidden" name="storage_type_id" id="storage_type_id"  value="<?php echo $storage_data['storage_type_id'];?>" /> 
</div> 

<script type="text/javascript">
$(document).ready(function(){
	
	var storage_type ='<?php echo @$storage_data['storage_type'];?>'

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
           data :{"storage_sn":storage_sn,"sku_id":sku_id,"before_count":before_count,"enter_depot":enter_depot,"storage_type":storage_type},
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
 </script>
 </body>
 </html>
  
