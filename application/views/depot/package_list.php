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
   <title>库存列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form id="print" method="post">

<input type="text" name="search" id="search" value="<?php echo @$search;?>"  placeholder="客户电话/档口/开单人" class="input-text lh25" size="30">
<input type="text" name="odo_date" id="odo_date" value="<?php echo @$data['odo_date'];?>"  placeholder="收包日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="10">
<SELECT class="select" id="status" name="status" style="width:120px;">
                    <OPTION value="">收包状态</OPTION>
                    <OPTION value="2">未收包</OPTION>
                    <OPTION value="1">已收包</OPTION>
</SELECT>
<input type="button" name="button" class="btn btn82 btn_search" onclick="" value="查询"> <span style='color:red;'>*支持模糊搜索</span>

</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
              
               <th width="5%">包号</th>
               <th width="10%">出库单号</th>
               <th width="15%">备注</th>
               <th width="8%">客户姓名</th>
               <th width="8%">客户电话</th>
               <th width="8%">发货方</th>
               <th width="8%">收货方</th>  
               <th width="8%">总数</th>
               <th width="10%">打包日期</th>                          
               <th width="10%">收包日期</th>
              </tr>
 
           <?php 
           foreach($package_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id=""> 
                <td ><?php echo $package_data[$k]['id']?></td>
                <td ><?php echo $package_data[$k]['odo_sn']?></td>
                <td ><?php echo $package_data[$k]['beizhu']?></td>               
                <td ><?php echo $package_data[$k]['client_name']?></td>
                <td ><?php echo $package_data[$k]['client_phone']?></td>
                <td ><?php echo $package_data[$k]['do_user_name']?></td>
                <td ><?php echo $package_data[$k]['shop_id']?></td>
                <td ><?php echo $package_data[$k]['count']?></td>
                <td >第<?php $a = explode(',',$package_data[$k]['type']);echo $a['0'];?>页/第<?php echo $a['1'];?>张</td>
                <td ><?php echo $package_data[$k]['pack_date']?></td>
                <td ><?php echo $package_data[$k]['collect_date']?></td>
              </tr>
         <?php 
           }
         ?>
              </table>
            <?php echo @$page_data;?>
        </div>
     </div>

   </div>

 </body>
 </html>
  
