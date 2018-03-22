 <!doctype html>
 <html lang="zh-CN">
 <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="/style/css/common.css">
   <link rel="stylesheet" href="/style/css/main.css">
   <script type="text/javascript" src="/style/js/jquery.min.js"></script>
   <script type="text/javascript" src="/style/js/colResizable-1.3.min.js"></script>
   <script type="text/javascript" src="/style/js/common.js"></script>
   <script type="text/javascript">
		 
   </script>
   <title>添加查看</title>
 </head>
 <body>
     <div id="forms" class="mt10">
        <div class="box">
          <div class="box_border">
           <div class="box_top"><b class="pl15">添加库位</b><b class="pl15" style="float: right;padding-right: 15px;"><a href="#" onclick="javascript:history.go(-1);">返回上一页</a></b></div>
           
            <div class="box_center">
            <form action="" method="post" class="jqtransform" enctype="multipart/form-data">
               <table class="form_table pt15 pb15" width="100%" border="0" cellpadding="0" cellspacing="0" id="add_tr">
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 库位名称:</td>
                  <td style="width:100px;"><input type="text" name="pos_name" id="pos_name"  value="<?php echo @$pos_data['pos_name'];?>" class="input-text lh25" size="40"></td>  
                  <td class="td_right"><span class="bitian">*</span> 所属仓库:</td>
                  <td>
                  <select name="did" id="did" class="select" >
                  <?php 
                  foreach(@$depot_data as $k=>$v){
                  	echo '<option value="'.$depot_data[$k]['id'].'" >'.$depot_data[$k]['depot_name'].'</option>';
                  }
                  ?>
                  </select>
                  </td>         
               </tr>
               <tr>
                  <td class="td_right"><span class="bitian">*</span> 库位代号:</td>
                  <td><input type="text" name="pos_code" id="pos_code"  value="<?php echo @$pos_data['pos_code'];?>" class="input-text lh25" size="40"></td> 
                  <td class="td_right">备注:</td>
                  <td><input type="text" name="beizhu" id="beizhu"  value="<?php echo @$pos_data['beizhu'];?>" class="input-text lh25" size="40"></td>                     
               </tr>
               </table>
               
               <div class="search_bar_btn" style="text-align:left;margin-left:220px;"> 
               <input type="hidden" name="hide_id" id="hide_id"  value="<?php echo @$pos_data['id'];?>" id="hidden1"/>  
               
               <input type="button" value="提交" onclick="add_pos()" class="btn btn82 btn_save2">
               <input type="reset" class="btn btn82 btn_res" value="重置">  
              </div>
              </div>
               </form>
               
            </div>
          </div>
        </div>
     </div>

  </div> 
   <script type="text/javascript">
   function add_pos(){
	   var pos_name = $("#pos_name").val();
	   var pos_code = $("#pos_code").val();
	   var beizhu = $("#beizhu").val();
	   var did = $("#did").val();
	   var id = $("#hide_id").val();

	   if(!pos_name||!pos_code||!did){
		   alert('仓库名字、仓库地址和仓库联系人必填');return;
	   }
		 $.ajax({
             url:"/depot/depot/add_pos",
             type:"POST",
             data:{"id":id,"pos_name":pos_name,'pos_code':pos_code,"did":did,"beizhu":beizhu},
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
   
	$("#did").val('<?php echo @$pos_data['did'];?>');
 </script>
 </body>
 </html>
  
