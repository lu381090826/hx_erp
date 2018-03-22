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
   <title>出库单打印</title>
 </head>
 <body style="height:100%">
  
<div style="margin-top:2%;margin-bottom:70px;">
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="show_table">
              <tr>
               <th width="10%">出库单号</th>
               <th width="10%">出库仓库</th>
               <th width="10%">制单人</th>
               <th width="10%">出库总数</th>  
               <th width="10%">出库日期</th>  
               <th width="20%">备注</th>           
              </tr>

              <tr class='lr' align='center'">  
                <td ><?php echo @$data['odo_data']['odo_sn'];?></td>
                <td ><?php echo @$data['odo_data']['depot_name'];?></td>
                <td ><?php echo @$data['odo_data']['name'];?></td>
                <td ><?php echo @$data['odo_data']['total_count'];?></td>
                <td ><?php echo @$data['odo_data']['odo_date'];?></td>
                <td ><?php echo @$data['odo_data']['beizhu'];?></td>
              </tr>
              </table>
              
        </div>
     </div>

   </div>        
        <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="add_table">
              <tr>
               <th width="10%">销售单号</th>
               <th width="10%">报货单号</th>
               <th width="10%">所在库位</th> 
               <th width="10%">skuid</th>
               <th width="10%">款号</th>
               <th width="10%">颜色</th>               
               <th width="10%">尺码</th>
               <th width="10%">报货数量</th>               
               <th width="10%">配货数量</th>
               
              </tr>
              
           <?php 
           foreach($data['odo_detail_data'] as $k=>$v){
           ?>
              <tr class='tr' align='center' > 
                <td ><?php echo $data['odo_detail_data'][$k]['sell_order']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['allocate_order']?></td>
                 <td ><?php echo $data['odo_detail_data'][$k]['pos_name']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['sku_id']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['spu']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['color']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['size']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['allocate_count']?>/<?php echo $data['odo_detail_data'][$k]['send_num']?></td>
                <td ><?php echo $data['odo_detail_data'][$k]['send_count']?></td>
               
              </tr>
         <?php 
           }
         ?>
              </table>              
        </div>
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
  
