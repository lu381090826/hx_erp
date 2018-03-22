 <!doctype html>
 <html lang="zh-CN">
 <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="/style/css/common.css">
   <link rel="stylesheet" href="/style/css/main.css">
   <link rel="stylesheet" href="/style/css/page.css">
   <script type="text/javascript" src="/style/js/jquery.min.js"></script>
   <script type="text/javascript" src="/style/js/Calendar.js"></script>
   <script type="text/javascript" src="/style/js/colResizable-1.3.min.js"></script>
   <script type="text/javascript" src="/style/js/common.js"></script>

   <script type="text/javascript">
		 
   </script>
   <title>出库单详情</title>
 </head>
 <body style="height:100%">
     <div id="forms" class="mt10">
        <div class="box">
          <div class="box_border">
           <div class="box_top"><b class="pl15">出库单详情</b><b class="pl15" style="float: right;padding-right: 15px;"><a href="#" onclick="javascript:history.go(-1);">返回上一页</a></b></div>
            <div class="box_center">
            <form action="" method="post" class="jqtransform" enctype="multipart/form-data">
               <table class="form_table pt15 pb15" width="100%" border="0" cellpadding="0" cellspacing="0" id="add_tr">
               <tr> 
               <td class="td_right"><span class="bitian">*</span> 出库类型:</td>
               <td><select name="enter_depot" id="enter_depot" class="select" disabled="true">
               <option value="1" >销售出库</option>
               <option value="2" >其他出库</option>
               </select></td>
               
               <td class="td_right"><span class="bitian">*</span> 出库仓库:</td>
               <td><select name="source_depot" id="source_depot" class="select" disabled="true"><?php foreach(@$depot_data as $k=>$v){echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?></select></td>
               
               <td class="td_right"><span class="bitian">*</span> 经办人:</td>
               <td><input type="text" name="name" id="name"  disabled="true" value="<?php echo @$data['odo_data']['name'];?>" class="input-text lh25" size="20"></td>
               </tr>
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 出库单号:</td>
                  <td style="width:100px;"><input type="text" name="odo_sn" id="odo_sn"  disabled="true" value="<?php echo @$data['odo_data']['odo_sn'];?>" disabled="true" style="background-color: rgba(10, 3, 31, 0.2);"class="input-text lh25" size="40"></td> 
                  <td class="td_right"> 备注:</td>
                  <td style="width:100px;"><input type="text" name="beizhu" id="beizhu"  value="<?php echo @$data['odo_data']['beizhu'];?>" class="input-text lh25" size="40"></td>
                  <td class="td_right"><span class="bitian">*</span> 出库日期:</td>
                  <td><input type="text" name="odo_date" id="odo_date" disabled="true" value="<?php echo @$data['odo_data']['odo_date'];?>" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true"  class="input-text lh25" size="20"></td>                             
               </tr>               

               </table>

              </div>
               </form>
               
            </div>
          </div>
        </div>


<div style="margin-top:2%;margin-bottom:70px;">
        <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="add_table">
              <tr>
               <th width="10%">销售单号</th>
               <th width="10%">报货单号</th>
               <th width="10%">sku</th>
               <th width="10%">款号</th>
               <th width="10%">颜色</th>               
               <th width="10%">尺码</th>
               <th width="10%">报货数量</th>
               <th width="10%">库存数量</th>
               <th width="10%">所在库位</th>
               <th width="10%">配货数量</th>   
              </tr>
              
           <?php 
           foreach($data['odo_detail_data'] as $k=>$v){
           ?>
              <tr class='tr' align='center' > 
                <td ><?php echo $data['odo_detail_data'][$k]['sell_order']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['allocate_order']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['sku_id']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['spu']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['color']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['size']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['allocate_count']?>/<?php echo $data['odo_detail_data'][$k]['send_num']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['stock_count']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['pos_name']?></td>
                <td ><input type="text" name="number<?php echo $k?>" id ="number<?php echo $k?>" value="<?php echo $data['odo_detail_data'][$k]['send_count']?>" placeholder="请输入数量" class="input-text lh25" size="8"></td>
              </tr>
         <?php 
           }
         ?>
              </table>              
        </div>
     </div>
</div>

<div class="search_bar_btn" id="set_button"> 

<div>
<input type="hidden" name="hide_id" id="hide_id"  value="<?php echo @$pos_data['id'];?>" id="hidden1"/> 
<input type="button" value="修改" onclick="add_odo()" class="btn btn82 btn_save2"> 
</div> 
</div>  

<script type="text/javascript">
function add_odo(){
	   var id = $("#hide_id").val();
	   var odo_sn = $("#odo_sn").val();
	   !odo_sn?odo_sn = $("#odo_sn").html():odo_sn;
	   var odo_date = $("#odo_date").val();
	   var enter_depot = $("#enter_depot").val();
	   var name = $("#name").val();
	   var source_depot = $("#source_depot").val();
	   var beizhu = $("#beizhu").val();
       var data = '<?php echo json_encode($odo_data);?>'

       var count = '<?php echo count($odo_data);?>'


   	   var  params = [];  
       for(var i = 0; i < count; i++){  
           var param = [];  
          var get_count = $("#number"+i).val();
          if(isPositiveInteger(get_count)==false){
              alert("请输入正整数");return;
          }
           params.push(get_count);  
       }  
         
       var send_count_json = JSON.stringify(params);  
       

      
	   if(!name){
		   alert('经办人不能为空！');return;
	   }
	   if(!odo_date){
		   alert('出库日期不能为空！');return;
	   }
	   
	   if(!enter_depot){
		   alert('进货仓库不能为空！');return;
	   }
	   
	   if(!source_depot){
		   alert('出库仓库不能为空！');return;
	   }
	   if(source_depot==enter_depot){
			   alert('进货仓库不能与出库仓库一样');return;
	   }
	  $.ajax({
       url:"/depot/offer/add_odo",
       type:"POST",
       data:{"id":id,"data":data,"odo_sn":odo_sn,'odo_date':odo_date,"enter_depot":enter_depot,"name":name,"source_depot":source_depot,"beizhu":beizhu,"send_count_json":send_count_json},
       dataType:"json",
       async:false,
       error: function() {
           //alert('服务器超时，请稍后再试');
       },
       success:function(data){                
          if(data.result=='1'){
              alert(data.msg);
              window.location.href= "/depot/offer/odo_list_view";
        }
        else{
              alert(data.msg);
         }           
       }
   });
}

function isPositiveInteger(s){//是否为正整数
    var re = /^[0-9]+$/ ;
    return re.test(s)
}   

$("#source_depot").val('<?php echo @$data['odo_data']['source_depot'];?>');
 </script>
 </body>
 </html>
  
