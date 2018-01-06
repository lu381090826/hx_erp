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
   <script type="text/javascript" src="/style/js/storage.js"></script>
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
                  <td style="width:100px;"><input type="text" name="storage_sn" id="storage_sn"  value="<?php echo @$storage_sn;?>" disabled="true" style="background-color: rgba(10, 3, 31, 0.2);"class="input-text lh25" size="40"></td> 
                  <td class="td_right"><span class="bitian">*</span> 手工单号:</td>
                  <td style="width:100px;"><input type="text" name="sn" id="sn"  value="<?php echo @$pos_data['sn'];?>" class="input-text lh25" size="40"></td>
                  <td class="td_right"><span class="bitian">*</span> 入库日期:</td>
                  <td><input type="text" name="storage_date" id="storage_date" value="<?php echo date("Y-m-d",time());?>" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true"  class="input-text lh25" size="20"></td>                             
               </tr>
               <tr> 
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
               <td class="td_right"><span class="bitian">*</span> 进入仓库:</td>
               <td><select name="enter_depot" id="enter_depot" class="select" ><?php foreach(@$depot_data as $k=>$v){echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?></select></td>
                  <td class="td_right"><span class="bitian">*</span> 经办人:</td>
                  <td><input type="text" name="name" id="name"  value="<?php echo @$_SESSION['name'];?>" class="input-text lh25" size="20"></td>
               </tr>               
               <tr >
               <td class="td_right"><span class="bitian">*</span> 供应商:</td>
               <td>
               <select name="supplier" id="supplier" class="select" >
               <option value="">请选择供应商</option>
               <?php foreach(@$supplier_data as $k=>$v)
               {echo '<option value="'.$supplier_data[$k]['id'].'" >'.$supplier_data[$k]['supplier_name'].'</option>';} ?>
               </select>
               </td>
               
               <td class="td_right"><span class="bitian">*</span> 调拨仓库:</td><td><select name="source_depot" id="source_depot" class="select" ><?php foreach(@$depot_data as $k=>$v){echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';} ?></select></td>
              
               <td class="td_right"><span class="bitian">*</span> 退单号:</td>
               <td><select name="return_sn" id="return_sn" class="select1" >
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
              </tr>
              </table>
        </div>
     </div>
</div>

<div  id="s_page" style="margin-bottom:70px;">

</div>
<div class="search_bar_btn" id="set_button"> 

<div>
<input type="hidden" name="hide_id" id="hide_id"  value="<?php echo @$pos_data['id'];?>" id="hidden1"/> 
<input type="button" value="提交" onclick="add_storage()" class="btn btn82 btn_save2"> 
</div> 
</div>  

<script type="text/javascript">
	$("#did").val('<?php echo @$pos_data['did'];?>');
 </script>
 </body>
 </html>
  
