
//添加入库数量
function add_count(sku_id,value){
	var storage_sn = $("#storage_sn").val();
	var storage_date = $("#storage_date").val();
	var source_depot = $("#source_depot").val();
	var storage_type = $("#storage_type").val();
	var depot_id = $("#depot_id").val();
	!storage_sn?storage_sn = $("#storage_sn").html():storage_sn;
	!storage_date?storage_date = $("#storage_date").html():storage_date;
	//验证输入框
    if((/^(\+|-)?\d+$/.test( value ))&&value>0){  
		 $.ajax({
             url:"/depot/storage/add_storage_sku",
             type:"POST",
             data:{"storage_sn":storage_sn,"sku_id":sku_id,"count":value,"status":'1',"depot_id":depot_id,"storage_date":storage_date,"source_depot":source_depot,"storage_type":storage_type},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result=='1'){
                	$(".dr").remove();
                	$("#s_page").html("");
	             	 var i;
	             	 for(i=0;i<data.data.length;i++){
	             		  $(".add_table").append('<tr class="dr" id="d'+data.data[i].sku_id+'" align="center"><td >'+data.data[i].goods_id+'</td><td >'+data.data[i].color+'</td><td >'+data.data[i].size+'</td><td ><input type="text" name="number1" id ="'+data.data[i].sku_id+'" value="'+data.data[i].count+'" onchange="change_count(this.id,this.value)" placeholder="请输入数量" class="input-text lh25" size="8"></td><td ><input type="text" name="beizhu" id ="'+data.data[i].sku_id+'"   value="'+data.data[i].beizhu+'" onchange="change_beizhu(this.id,this.value)" placeholder="请输入备注" class="input-text lh25" size=50"></td><td><a href="javascript:void(0)" onclick=delete_storage_sku_id("'+data.data[i].sku_id+'","'+data.data[i].storage_sn+'")>删除</a></td></tr>');	  
	             		  
			         }
	             	$("#s_page").append(data.page);
	             	change_dr();
	             	 
              }
              else{
            	  $("#notice").html(data.msg);
               }           
             }
         });
	   
    }
    else{  
        alert("数量中请输入正整数！！");  
        $("#"+id).val('');
        return false;  
    } 
}

//改变入库数量
function change_count(sku_id,value){
	var storage_sn = $("#storage_sn").val();
	!storage_sn?storage_sn = $("#storage_sn").html():storage_sn;
	var enter_depot = $("#depot_id").val();
	var source_depot_id = $("#source_depot_id").val();
	var storage_type_id = $("#storage_type_id").val();
	//验证输入框
    if((/^(\+|-)?\d+$/.test( value ))&&value>0){  
		 $.ajax({
             url:"/depot/storage/change_storage_sku",
             type:"POST",
             data:{"storage_sn":storage_sn,"sku_id":sku_id,"count":value,"status":'1',"enter_depot":enter_depot,"source_depot":source_depot_id,"storage_type":storage_type_id},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                 if(data.result!='1'){
                 	alert(data.msg);
                }         
             }
         });
	   
    }
    else{  
        alert("数量中请输入正整数！");  
        $("#"+id).val('');
        return false;  
    } 
}

//改变入库单详情备注
function change_beizhu(sku_id,beizhu){
	var storage_sn = $("#storage_sn").val(); 
	!storage_sn?storage_sn = $("#storage_sn").html():storage_sn;
		 $.ajax({
             url:"/depot/storage/change_storage_sku_beizhu",
             type:"POST",
             data:{"storage_sn":storage_sn,"sku_id":sku_id,"beizhu":beizhu},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result!='1'){
                	alert(data.msg);
               }          
             }
         });
}
//改变入库单备注
function change_storage_beizhu(){

	var storage_sn = $("#storage_sn").val(); 
	var beizhu = $(".storage_beizhu").val();
	
	!storage_sn?storage_sn = $("#storage_sn").html():storage_sn;
	!beizhu?beizhu = $(".storage_beizhu").html():beizhu;
		 $.ajax({
             url:"/depot/storage/change_storage_beizhu",
             type:"POST",
             data:{"storage_sn":storage_sn,"beizhu":beizhu},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result!='1'){
                	alert(data.msg);
               }          
             }
         });
}

//分页获取数据

   function jump_page(i){
	   var spu = $("#spu").val();
	   
		 $.ajax({
             url:"/depot/storage/get_spu_sku",
             type:"POST",
             data:{"spu":spu,"page":i},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result=='1'){
                	$(".tr").remove();
                	$("#page").html("");
                	$("#notice").html("");
	             	 var i;
	             	 for(i=0;i<data.data.length;i++){
	             		$(".list_table").append('<tr class="tr" align="center"><td >'+data.data[i].goods_id+'</td><td >'+data.data[i].name+'</td><td >'+data.data[i].size_info+'</td><td ><input type="text" name="number1" id ="'+data.data[i].sku_id+'" onchange="add_count(this.id,this.value)" placeholder="请输入数量" class="input-text lh25" size="8"></td></tr>');	  
			         }
	             	$("#page").append(data.page);
	             	change_tr();
	             	 
              }
              else{
            	  $("#notice").html(data.msg);
               }           
             }
         });
	   
   }

 //分页2获取数据

   function order_page(i){
	   var storage_sn = $("#storage_sn").val();
	  
	   var depot_id = $("#depot_id").val();
	    !storage_sn?storage_sn = $("#storage_sn").html():storage_sn
	    !depot_id?depot_id = $("#depot_id").html():depot_id
		 $.ajax({
             url:"/depot/storage/get_order_list_page",
             type:"POST",
             data:{"storage_sn":storage_sn,"page":i,"enter_depot":depot_id},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result=='1'){
                	$(".dr").remove();
                	$("#s_page").html("");
	             	 var i;
	             	 for(i=0;i<data.data.length;i++){
	             		  $(".add_table").append('<tr class="dr" id="d'+data.data[i].sku_id+'" align="center"><td >'+data.data[i].goods_id+'</td><td >'+data.data[i].color+'</td><td >'+data.data[i].size+'</td><td ><input type="text" name="number1" id ="'+data.data[i].sku_id+'" value="'+data.data[i].count+'" onchange=change_count(this.id,this.value) placeholder="请输入数量" class="input-text lh25" size="8"></td><td ><input type="text" name="beizhu" id ="b'+data.data[i].sku_id+'"   placeholder="请输入备注" value="'+data.data[i].beizhu+'" onchange="change_beizhu(this.id,this.value)" class="input-text lh25" size=50"></td><td><a href="javascript:void(0)" onclick=delete_storage_sku_id("'+data.data[i].sku_id+'","'+data.data[i].storage_sn+'")>删除</a></td></tr>');	  
	             		  
			         }
	             	$("#s_page").append(data.page);
	             	change_dr();
	             	 
              }         
             }
         });
	   
   }
   
  
 