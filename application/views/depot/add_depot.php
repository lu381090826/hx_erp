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
           <div class="box_top"><b class="pl15">添加仓库</b></div>
            <div class="box_center">
            <form action="" method="post" class="jqtransform" enctype="multipart/form-data">
               <table class="form_table pt15 pb15" width="100%" border="0" cellpadding="0" cellspacing="0" id="add_tr">
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 仓库名称:</td>
                  <td style="width:100px;"><input type="text" name="depot_name" id="depot_name"  value="<?php echo @$depot_data['depot_name'];?>" class="input-text lh25" size="40"></td>  
                  <td class="td_right"><span class="bitian">*</span> 仓库联系人:</td>
                  <td><input type="text" name="name" id="name"  value="<?php echo @$depot_data['name'];?>" class="input-text lh25" size="40"></td>         
               </tr>
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 仓库地址:</td>
                  <td><input type="text" name="depot_address" id="depot_address"  value="<?php echo @$depot_data['depot_address'];?>" class="input-text lh25" size="40"></td> 
                  <td class="td_right">联系人电话:</td>
                  <td><input type="text" name="mobile" id="mobile"  value="<?php echo @$depot_data['mobile'];?>" class="input-text lh25" size="40"></td>                     
               </tr>
               <tr>
                  <td class="td_right">备注:</td>
                  <td><input type="text" name="beizhu" id="beizhu"  value="<?php echo @$depot_data['beizhu'];?>" class="input-text lh25" size="40"></td>    
                  <td class="td_right">选为默认仓库:</td>
                  <td><INPUT type=checkbox value="" name="type" id="type" <?php if(@$depot_data['type']==1){echo 'checked="checked"';}?>></td>                  
               </tr>
               </table>
               
               <div class="search_bar_btn" style="text-align:left;margin-left:220px;"> 
               <input type="hidden" name="hide_id" id="hide_id"  value="<?php echo @$depot_data['id'];?>" id="hidden1"/>  
               
               <input type="button" value="提交" onclick="add_depot()" class="btn btn82 btn_save2">
               <input type="reset" class="btn btn82 btn_res" value="重置">  
              </div>
              </div>
               </form>
               
            </div>
          </div>
        </div>
        
     
   <script type="text/javascript">
  function add_depot(){
	  if($("input[type='checkbox']").is(':checked')==true){
		  var type = 1;
	  }
	  else{
		  var type = 0;
	  }
	   var depot_name = $("#depot_name").val();
	   var depot_address = $("#depot_address").val();
	   var name = $("#name").val();
	   var mobile = $("#mobile").val();
	   var beizhu = $("#beizhu").val();
       var id = $("#hide_id").val();
	   if(!depot_name||!depot_address||!name){
		   alert('仓库名字、仓库地址和仓库联系人必填');return;
	   }
		 $.ajax({
             url:"/depot/depot/add_depot",
             type:"POST",
             data:{"id":id,"depot_name":depot_name,'depot_address':depot_address,"name":name,"mobile":mobile,"type":type,"beizhu":beizhu},
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
 </script>
 </body>
 </html>
  
