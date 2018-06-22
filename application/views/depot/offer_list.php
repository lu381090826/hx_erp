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
   <style>

.mybtn
{  
      
    height:35px;
	width:82px;
    text-decoration:none;  
    background:#46A3FF;  
    color:#f2f2f2;  
    cursor: pointer;  
    padding: 7px 10px 10px 10px;  
    font-size:16px;  
 
    font-family: 微软雅黑,宋体,Arial,Helvetica,Verdana,sans-serif;  
    font-weight:bold;  
    border-radius:25px;  
      
    -webkit-transition:all linear 0.30s;  
    -moz-transition:all linear 0.30s;  
    transition:all linear 0.30s;  
      
    }  
.mybtn:hover {
background:#842B00; 
}
    
   </style>
   <title>库存列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form action="/depot/offer/offer_list_where_view" method="get">

<input type="text" name="search" id="search" value="<?php echo @$search_data['search'];?>"  placeholder="销售单号/开单人/客户名字/客户电话" class="input-text lh25" size="40">
<input type="text" name="start_date" id="start_date" value="<?php echo @$search_data['start_date'];?>"  placeholder="开始日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">

<input type="text" name="end_date" id="end_date" value="<?php echo @$search_data['end_date'];?>"  placeholder="结束日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">

<SELECT class="select" id="allocate_mode" name="allocate_mode" style="width:120px;">
                    <OPTION value="">配货方式</OPTION>
                    <OPTION value="0">默认</OPTION>
                    <OPTION value="1">定制</OPTION>
                    <OPTION value="2">齐了配</OPTION>
                    <OPTION value="3">单款齐配</OPTION>

</SELECT>
<input type="submit" name="button" class="btn btn82 btn_search"  value="查询"> <span style='color:red;'>*支持模糊搜索</span> 


<input type="button" name="button" class="mybtn" onclick="peihuo()" value="手动审核">

<input type="button" name="button" class="mybtn" onclick="auto()" value="自动审核">
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="2%"><INPUT type=checkbox value="" name=toggle  onclick="swapCheck()"></th>
               <th width="8%">销售单号</th>
               <th width="3%">开单人</th>
               <th width="5%">客户名字</th>
               <th width="6%">客户电话</th>
               <th width="8%">报货单号</th>  
               <th width="5%">sku</th>           
               <th width="5%">款号</th>
               <th width="3%">颜色</th>
               <th width="3%">尺码</th>
               <th width="5%">数量</th>
               <th width="5%">可用库存</th>
               <th width="5%">所在库位</th>
               <th width="3%">未配</th>
               <th width="3%">已配</th>
               <th width="5%">配货方式</th>
               <th width="5%">收货方式</th>
               <th width="5%">付款方式</th>
               <th width="8%">开单时间</th>
               <th width="8%">报货时间</th>               
              </tr>
 
           <?php 
           foreach(@$offer_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id=""> 
                <td align=middle><INPUT id="cblist"  name="cblist" type=checkbox value="<?php echo $offer_data[$k]['id']?>"></td> 
                <td ><?php echo $offer_data[$k]['order_num']?></td>
                <td ><?php echo $offer_data[$k]['user_name']?></td>               
                <td ><?php echo $offer_data[$k]['client_name']?></td>
                <td ><?php echo $offer_data[$k]['client_phone']?></td>
                <td ><?php echo $offer_data[$k]['allocate_num']?></td>
                <td ><?php echo $offer_data[$k]['sku_id']?></td>
                <td ><?php echo $offer_data[$k]['spu']?></td>
                <td ><?php echo $offer_data[$k]['color']?></td>
                <td ><?php echo $offer_data[$k]['size']?></td>
                <td ><?php echo $offer_data[$k]['num']?>/<?php echo $offer_data[$k]['send_num']?></td>
                <td ><?php echo $offer_data[$k]['stock_count']?></td>
                <td ><?php echo $offer_data[$k]['pos_name']?></td>
                <td ><?php echo $offer_data[$k]['weipei_count']?></td>
                <td ><?php echo $offer_data[$k]['send_count']?></td>
                <td ><?php echo @$offer_data[$k]['allocate_mode']?></td>
                <td ><?php echo @$offer_data[$k]['delivery_type']?></td>
                <td ><?php echo @$offer_data[$k]['payment']?></td>
                <td ><?php echo $offer_data[$k]['create_at']?></td>
                <td ><?php echo $offer_data[$k]['allocate_create_at']?></td>
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

function peihuo(){
   var chk_value =[]; 
	$('input[name="cblist"]:checked').each(function(){ 
	chk_value.push($(this).val()); 
	});
	 if(chk_value.length==0){
           alert('请选择需要手动审核的报货单！');return;
     }

	 window.location.href= "/depot/offer/add_odo_view?id_list="+chk_value;
}

var isCheckAll = false;  

function swapCheck() {  
	   //取消全选
    if (isCheckAll) {  
        $("input[type='checkbox']").each(function() {  
            this.checked = false;  
        }); 
        isCheckAll = false;  
    } 
    //全选
    else {  
        $("input[type='checkbox']").each(function() {  
            this.checked = true;  
        });  
        isCheckAll = true;  
    }  
}  

function auto(){
	if(window.confirm('你确定要审核当前所有报货单吗？')){
		 $.ajax({     
           type:"POST",       
           url:"/depot/offer/auto_check",
           dataType:"json",
           cache:false,
           beforeSend: function() {
           },     
           success:function(data){ 
          	 
          	if(data.result=='1'){
          		alert(data.msg);
          		window.location.href='/depot/offer/odo_list_view';		
            } 
          	else{
          		alert(data.msg);
            }
           	                	 
           },   
  
        });
    }else{
       //alert("取消");
       return false;
   }
}

$("#allocate_mode").val('<?php echo @$search_data['allocate_mode'];?>');

</script>
 </body>
 </html>
  
