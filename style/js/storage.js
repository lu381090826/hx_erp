
//添加入库数量
function add_count(sku_id,value){
	var storage_sn = $("#storage_sn").val();
	//验证输入框
    if((/^(\+|-)?\d+$/.test( value ))&&value>0){  
		 $.ajax({
             url:"/depot/storage/add_storage_sku",
             type:"POST",
             data:{"storage_sn":storage_sn,"sku_id":sku_id,"count":value},
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
	             		  $(".add_table").append('<tr class="dr"  align="center"><td >'+data.data[i].goods_id+'</td><td >'+data.data[i].color+'</td><td >'+data.data[i].size+'</td><td ><input type="text" name="number1" id ="'+data.data[i].sku_id+'" value="'+data.data[i].count+'" onchange="change_count(this.id,this.value)" placeholder="请输入数量" class="input-text lh25" size="8"></td><td ><input type="text" name="beizhu" id ="'+data.data[i].sku_id+'"   value="'+data.data[i].beizhu+'" onchange="change_beizhu(this.id,this.value)" placeholder="请输入备注" class="input-text lh25" size=50"></td></tr>');	  
	             		  
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
        alert("数量中请输入正整数！");  
        $("#"+id).val('');
        return false;  
    } 
}

//改变入库数量
function change_count(sku_id,value){
	var storage_sn = $("#storage_sn").val();
	//验证输入框
    if((/^(\+|-)?\d+$/.test( value ))&&value>0){  
		 $.ajax({
             url:"/depot/storage/change_storage_sku",
             type:"POST",
             data:{"storage_sn":storage_sn,"sku_id":sku_id,"count":value},
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

//改变入库备注
function change_beizhu(sku_id,beizhu){
	var storage_sn = $("#storage_sn").val(); 
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
	   
		 $.ajax({
             url:"/depot/storage/get_order_list_page",
             type:"POST",
             data:{"storage_sn":storage_sn,"page":i},
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
	             		  $(".add_table").append('<tr class="dr"  align="center"><td >'+data.data[i].goods_id+'</td><td >'+data.data[i].color+'</td><td >'+data.data[i].size+'</td><td ><input type="text" name="number1" id ="'+data.data[i].sku_id+'" value="'+data.data[i].count+'" onchange="change_count(this.id,this.value)" placeholder="请输入数量" class="input-text lh25" size="8"></td><td ><input type="text" name="beizhu" id ="'+data.data[i].sku_id+'"   placeholder="请输入备注" value="'+data.data[i].beizhu+'" onchange="change_beizhu(this.id,this.value)" class="input-text lh25" size=50"></td></tr>');	  
	             		  
			         }
	             	$("#s_page").append(data.page);
	             	change_dr();
	             	 
              }         
             }
         });
	   
   }
   
   $(document).ready(function(){
	   $("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	   $("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	   $("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	   $('#storage_type').change(function(){
	       var type =  $(this).children('option:selected').val();
	        switch(type){
	        case"1":
	        	  $("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	  $("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	  $("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
		    
	        case"2":
	        	alert('销售退货入库流程正在开发中，请等待！');return;
	        	$("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	$("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
		    
	        case"3":
	        	$("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	$("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
		    
	        case"4":
	        	$("#source_depot,#return_sn,#supplier").attr("disabled",false).css("background-color","");
	        	$("#source_depot").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#supplier").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
	        	$("#return_sn").attr("disabled","disabled").css("background-color","rgba(10, 3, 31, 0.2)");
		    break;
	        }
	   })
  })
	   
   function add_storage(){
	   var id = $("#hide_id").val();
	   var storage_sn = $("#storage_sn").val();
	   var sn = $("#sn").val();
	   var storage_date = $("#storage_date").val();
	   var storage_type = $("#storage_type").val();
	   var enter_depot = $("#enter_depot").val();
	   var name = $("#name").val();
	   var supplier = $("#supplier").val();
	   var source_depot = $("#source_depot").val();
	   var beizhu = $("#beizhu").val();

	   if(!storage_sn||!sn||!storage_date||!storage_type||!enter_depot){
		   alert('内容不能为空');return;
	   }
		 $.ajax({
             url:"/depot/storage/add_storage",
             type:"POST",
             data:{"id":id,"storage_sn":storage_sn,'sn':sn,"storage_date":storage_date,"storage_type":storage_type,"enter_depot":enter_depot,"name":name,"supplier":supplier,"source_depot":source_depot,"beizhu":beizhu},
             dataType:"json",
             async:false,
             error: function() {
                 //alert('服务器超时，请稍后再试');
             },
             success:function(data){                
                if(data.result=='1'){
                    alert(data.msg);
              }
              else{
                    alert(data.msg);
               }           
             }
         });
  }
