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
   <title>退单列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form id="print" action="/depot/offer/get_refund_list_where_view" method="get">

<input type="text" name="search" id="search" value="<?php echo @$search_data['search'];?>"  placeholder="订单号/客户姓名/客户电话/填单人" class="input-text lh25" size="30">
<SELECT class="select" id="status" name="status" style="width:120px;">
                    <OPTION value="">全部状态</OPTION>
                    <OPTION value="1">已审核</OPTION>
                    <OPTION value="0">未审核</OPTION>
</SELECT>
<input type="submit" name="button" class="btn btn82 btn_search"  value="查询"> <span style='color:red;'>*支持模糊搜索</span>

</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="10%">销售单号</th>
               <th width="10%">退单号</th>
               <th width="8%">客户姓名</th>
               <th width="8%">客户电话</th>
               <th width="8%">填单人</th>
               <th width="10%">填单时间</th>  
               <th width="8%">退货总数</th>
               <th width="15%">备注</th>                          
               <th width="8%">审核状态</th>
               <th width="8%">操作</th>
              </tr>
 
           <?php 
           foreach($refund_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id=""> 
                <td ><?php echo $refund_data[$k]['sell_order']?></td>
                <td ><?php echo $refund_data[$k]['order_num']?></td>               
                <td ><?php echo $refund_data[$k]['client_name']?></td>
                <td ><?php echo $refund_data[$k]['client_phone']?></td>
                <td ><?php echo $refund_data[$k]['user_name']?></td>
                <td ><?php echo $refund_data[$k]['create_at']?></td>
                <td ><?php echo $refund_data[$k]['total_num']?></td>
                <td ><?php echo $refund_data[$k]['remark']?></td>
                <td ><?php echo $refund_data[$k]['status']?></td>
                <td >详情</td>
              </tr>
         <?php 
           }
         ?>
              </table>
            <?php echo @$page_data;?>
        </div>
     </div>

   </div>
<script>
$("#status").val('<?php echo @$search_data['status'];?>');
</script>
 </body>
 </html>
  
