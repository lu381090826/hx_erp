 <!doctype html>
 <html lang="zh-CN">
 <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="/style/css/common.css">
   <link rel="stylesheet" href="/style/css/main.css">
   <script type="text/javascript" src="/style/js/jquery.min.js"></script>
   <script type="text/javascript" src="/style/js/colResizable-1.3.min.js"></script>
   <script type="text/javascript" src="/style/js/common.js"></script>
   <title>添加查看</title>
 </head>
 <body>
     <div id="forms" class="mt10">
        <div class="box">
          <div class="box_border">
           <div class="box_top"><b class="pl15">添加供应商</b></div>
            <div class="box_center">
            <form action="" method="post" class="jqtransform" enctype="multipart/form-data">
               <table class="form_table pt15 pb15" width="100%" border="0" cellpadding="0" cellspacing="0" id="add_tr">
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 供应商名称:</td>
                  <td style="width:100px;"><input type="text" name="supplier_name" id="supplier_name"  value="<?php echo @$supplier_data['supplier_name'];?>" class="input-text lh25" size="40"></td>  
                  <td class="td_right"><span class="bitian">*</span> 供应商地址:</td>
                  <td><input type="text" name="supplier_address" id="supplier_address"  value="<?php echo @$supplier_data['supplier_address'];?>" class="input-text lh25" size="40"></td>         
               </tr>
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 供应商联系人:</td>
                  <td><input type="text" name="name" id="name"  value="<?php echo @$supplier_data['name'];?>" class="input-text lh25" size="40"></td> 
                  <td class="td_right">联系人电话:</td>
                  <td><input type="text" name="mobile" id="mobile"  value="<?php echo @$supplier_data['mobile'];?>" class="input-text lh25" size="40"></td>                     
               </tr>
               <tr>
                  <td class="td_right">供应商银行账号:</td>
                  <td><input type="text" name="supplier_bank_number" id="supplier_bank_number"  value="<?php echo @$supplier_data['supplier_bank_number'];?>" class="input-text lh25" size="40"></td>    
                  <td class="td_right">备注:</td>
                  <td><input type="text" name="beizhu" id="beizhu"  value="<?php echo @$supplier_data['beizhu'];?>" class="input-text lh25" size="40"></td>                     
               </tr>
               </table>
               
               <div class="search_bar_btn" style="text-align:left;margin-left:220px;"> 
               <input type="hidden" name="hide_id" id="hide_id"  value="<?php echo @$supplier_data['id'];?>" id="hidden1"/>  
               
               <input type="button" value="提交" onclick="add_supplier()" class="btn btn82 btn_save2">
               <input type="reset" class="btn btn82 btn_res" value="重置">  
              </div>
              </div>
               </form>
               
            </div>
          </div>
        </div>
        
     
   <script type="text/javascript">
  function add_supplier(){
       var id  = $("#hide_id").val();
	   var supplier_name = $("#supplier_name").val();
	   var supplier_address = $("#supplier_address").val();
	   var name = $("#name").val();
	   var mobile = $("#mobile").val();
	   var beizhu = $("#beizhu").val();
       var supplier_bank_number = $("#supplier_bank_number").val();
       
	   if(!supplier_name||!supplier_address||!name||!mobile){
		   alert('内容不能为空');return;
	   }
		 $.ajax({
             url:"/depot/depot/add_supplier",
             type:"POST",
             data:{"id":id,"supplier_name":supplier_name,'supplier_address':supplier_address,"name":name,"mobile":mobile,"supplier_bank_number":supplier_bank_number,"beizhu":beizhu},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result=='1'){
                    alert(data.msg);
                    window.location.href= "/depot/depot/supplier_list_view";
              }
              else{
                    alert(data.msg);
               }           
             }
         });
  }
 </script>
 </body>
 </html>
  
